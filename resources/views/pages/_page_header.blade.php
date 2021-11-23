{!! $page->category->subject['term'].' ・ '.$page->category->displayName !!}{!! $page->category->subject['key'] == 'time' && isset($page->data['date']['start']) ? ' ・ '.$dateHelper->formatTimeDate($page->data['date']['start']).(isset($page->data['date']['start']) && isset($page->data['date']['end']) ? '-' : '' ).(isset($page->data['date']['end']) ? $dateHelper->formatTimeDate($page->data['date']['end']) : '') : '' !!}{!! $page->parent && (isset($page->parent->is_visible) ? ($page->parent->is_visible || (Auth::check() && Auth::user()->canWrite)) : 1) ? ' ・ '.$page->parent->displayName : '' !!}
<h1>
    {{ $page->title }}{!! !$page->is_visible ? ' <i class="fas fa-eye-slash" data-toggle="tooltip" title="This page is currently hidden"></i>' : '' !!}{{ isset($section) ? ' : '.$section : '' }}
    @if($page->protection && $page->protection->is_protected)
        <i class="fas fa-lock float-right" data-toggle="tooltip" title="This page {{ $page->protection->reason ? 'has been protected for the following reason: '.$page->protection->reason.'.' : 'is protected.' }} Only site admins may edit it."></i>
    @endif
</h1>
