@php($bundlePriceCurrency = $bundle->getPriceCurrency())

@if($bundle->price > 0)
    @if($bundle->bestTicket() < $bundle->price)
        <span class="">{{ handleBundlePriceByCurrency($bundle->bestTicket(), $bundlePriceCurrency) }}</span>
        <span class="font-14 font-weight-400 text-gray-500 ml-8 text-decoration-line-through">{{ handleBundlePriceByCurrency($bundle->price, $bundlePriceCurrency) }}</span>
    @else
        <span class="">{{ handleBundlePriceByCurrency($bundle->price, $bundlePriceCurrency) }}</span>
    @endif
@else
    <span class="">{{ trans('public.free') }}</span>
@endif
