<div class="sort-item">
    <a class="float-left fas fa-arrows-alt-v handle mr-3" href="#"></a>
    <a href="#" class="float-right remove-section btn btn-danger mb-2">×</a>
    <div class="row">
        <div class="col-md">
            <div class="form-group">
                {!! Form::label('Section Key') !!}
                {!! Form::text('section_key[]', null, ['class' => 'form-control', 'placeholder' => 'Internal key. Can\'t be duplicated in a template']) !!}
            </div>
        </div>
        <div class="col-md">
            <div class="form-group">
                {!! Form::label('Section Name') !!}
                {!! Form::text('section_name[]', null, ['class' => 'form-control', 'placeholder' => 'Section name/header']) !!}
            </div>
        </div>
    </div>
    <hr/>
</div>
