<?php

namespace App\Sessions;

use Illuminate\Support\Carbon;

class ZoomOAuth
{
    private ?string $lastError = null;

    private function getCredentials($user = null): array
    {
        $settings = getFeaturesSettings();
        $zoomApi = $user ? $user->zoomApi : null;

        return [
            'client_id' => !empty($zoomApi?->api_key) ? $zoomApi->api_key : (!empty($settings['zoom_client_id']) ? $settings['zoom_client_id'] : ''),
            'client_secret' => !empty($zoomApi?->api_secret) ? $zoomApi->api_secret : (!empty($settings['zoom_client_secret']) ? $settings['zoom_client_secret'] : ''),
            'account_id' => !empty($zoomApi?->account_id) ? $zoomApi->account_id : (!empty($settings['zoom_account_id']) ? $settings['zoom_account_id'] : ''),
        ];
    }

    private function handleConfigs($user = null)
    {
        $credentials = $this->getCredentials($user);

        \Config::set("zoom.client_id", $credentials['client_id']);
        \Config::set("zoom.client_secret", $credentials['client_secret']);
        \Config::set("zoom.account_id", $credentials['account_id']);
        \Config::set("zoom.base_url", "https://api.zoom.us/v2/");
    }

    public function hasCredentials($user = null): bool
    {
        $credentials = $this->getCredentials($user);

        return !empty($credentials['client_id'])
            && !empty($credentials['client_secret'])
            && !empty($credentials['account_id']);
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    private function normalizeZoomError(?string $message): string
    {
        if (empty($message)) {
            return trans('update.zoom_error_msg');
        }

        if (stripos($message, 'does not contain scopes') !== false || stripos($message, '4711') !== false) {
            return 'Zoom app is missing the scopes required to create meetings. Add meeting:write:meeting or meeting:write:meeting:admin in Zoom Marketplace, then reactivate the app.';
        }

        return $message;
    }

    public function makeMeeting($session, $user = null): bool
    {
        $this->lastError = null;
        $this->handleConfigs($user);

        $meeting = \Zoom::createMeeting([
            "agenda" => $session->title,
            "topic" => 'New meeting',
            "type" => 2, // 1 => instant, 2 => scheduled, 3 => recurring with no fixed time, 8 => recurring with fixed time
            "duration" => $session->duration, // in minutes
            "timezone" => 'UTC', // set your timezone
            "password" => $session->api_secret,
            "start_time" => new Carbon($session->date), // set your start time
            //"template_id" => 'set your template id', // set your template id  Ex: "Dv4YdINdTk+Z5RToadh5ug==" from https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/meetingtemplates
            "pre_schedule" => false,  // set true if you want to create a pre-scheduled meeting
            "schedule_for" => null, // set your schedule for profile email
            "settings" => [
                'join_before_host' => true, // if you want to join before host set true otherwise set false
                'host_video' => true, // if you want to start video when host join set true otherwise set false
                'participant_video' => false, // if you want to start video when participants join set true otherwise set false
                'mute_upon_entry' => false, // if you want to mute participants when they join the meeting set true otherwise set false
                'waiting_room' => false, // if you want to use waiting room for participants set true otherwise set false
                'audio' => 'both', // values are 'both', 'telephony', 'voip'. default is both.
                'auto_recording' => 'none', // values are 'none', 'local', 'cloud'. default is none.
                'approval_type' => 0, // 0 => Automatically Approve, 1 => Manually Approve, 2 => No Registration Required
            ],
        ]);


        if (!empty($meeting) and isset($meeting['status']) and $meeting['status']) {
            unset($session->title, $session->locale);

            $meetingData = $meeting['data'] ?? [];
            $isLiveCourse = method_exists($session, 'getTable') && $session->getTable() === 'live_courses';

            $resolvedLink = $meetingData['join_url'] ?? null;

            if ($isLiveCourse) {
                $resolvedLink = $meetingData['start_url'] ?? $resolvedLink;
            }

            $updateData = [
                'link' => $resolvedLink,
                'api_secret' => $meetingData['password'] ?? null,
            ];

            if (\Schema::hasColumn($session->getTable(), 'zoom_start_link')) {
                $updateData['zoom_start_link'] = $meetingData['start_url'] ?? null;
            }

            $session->update($updateData);

            return true;
        }

        $this->lastError = $this->normalizeZoomError($meeting['message'] ?? null);

        return false;
    }
}

