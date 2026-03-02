@props(['status'])

@php
$badgeType = 'badge badge-primary';

if($status == 'accepted'){
    $badgeType = 'badge badge-warning';
} elseif ($status == 'completed'){
    $badgeType = 'badge badge-success';
}

@endphp

<span class="{{$badgeType}} h-8 badge-xl font-semibold">{{ucfirst($status)}}</span>
