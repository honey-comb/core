@if(isset($config['listURL']) && ! empty($config['listURL']))
    contentURL: '{{ $config['listURL'] }}',
@endif

@if(isset($config['disableByFieldsPartially']) && ! empty($config['disableByFieldsPartially']))
    disableByFieldsPartially: {!! json_encode($config['disableByFieldsPartially']) !!},
@endif

@if(isset($config['disableByFieldsFully']) && ! empty($config['disableByFieldsFully']))
    disableByFieldsFully: {!! json_encode($config['disableByFieldsFully']) !!},
@endif

@if(isset($config['buttons']) && ! empty($config['buttons']))
    buttons: {!! json_encode($config['buttons']) !!},
@endif

@if(isset($config['images']) && ! empty($config['images']))
    images: {!! json_encode($config['images']) !!},
@endif

@if(isset($config['imagesUrl']) && ! empty($config['imagesUrl']))
    imagesURL: "{{ $config['imagesUrl'] }}",
@endif

forms: {
@if(isset($config['newFormUrl']) && ! empty($config['newFormUrl']))
    new: '{{ $config['newFormUrl'] }}',
@endif

@if(isset($config['newRecordUrl']) && ! empty($config['newRecordUrl']))
    newRecord: '{{ $config['newRecordUrl'] }}',
@endif

@if(isset($config['editFormUrl']) && ! empty($config['editFormUrl']))
    edit: '{{ $config['editFormUrl'] }}'
@endif
},

@if(isset($config['filters']) && ! empty($config['filters']))
    filters: {!! json_encode($config['filters']) !!},
@endif

@if(isset($config['actions']) && ! empty($config['actions']))
    actions: {!! json_encode($config['actions']) !!},
@endif

@if(isset($config['headers']) && ! empty($config['headers']))
    headers: {!! json_encode($config['headers']) !!},
@endif

@if(isset($config['type']) && ! empty($config['type']))
    type: {!! json_encode($config['type']) !!}
@else
    type: 'endless',
@endif

@if(isset($config['popUpLabel']) && ! empty($config['popUpLabel']))
    popUpLabel: '{{ $config['popUpLabel']}}'
@else
    popUpLabel: 'id'
@endif