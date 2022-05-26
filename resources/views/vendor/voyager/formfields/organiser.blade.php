<select class="form-control" 
    name="{{ $row->field }}"
    @if($row->required == 1) required @endif
>
    @if($row->required)
        <option value="">{{__('voyager::generic.none')}}</option>
    @endif

    @foreach($organisers as $organiser)
        <option value="{{ $organiser->id }}" 
            @if(isset($dataTypeContent->{$row->field}))
                @if($dataTypeContent->{$row->field} == $organiser->id)
                {{ 'selected="selected"' }}
                @endif
            @endif
        >
            {{ $organiser->name }} ( {{ $organiser->email }} )
        </option>
    @endforeach
</select>