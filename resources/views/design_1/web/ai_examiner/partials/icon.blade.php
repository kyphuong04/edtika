@if($icon === 'chart')
    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="12" width="4" height="8" rx="1"/><rect x="10" y="8" width="4" height="12" rx="1"/><rect x="16" y="4" width="4" height="16" rx="1"/></svg>
@elseif($icon === 'link')
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/></svg>
@elseif($icon === 'book')
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 5c3-1.3 6-1.3 9 .8 3-2.1 6-2.1 9-.8v14c-3-1.3-6-1.3-9 .8-3-2.1-6-2.1-9-.8z"/><path d="M12 5.8V20"/></svg>
@else
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"/><path d="M14 3v5h5M8.5 13h7M8.5 17h5"/></svg>
@endif
