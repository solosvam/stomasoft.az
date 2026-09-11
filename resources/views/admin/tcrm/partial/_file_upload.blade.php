<form action="{{ route('admin.patient.files.upload', $patient->id) }}" class="dropzone" id="dropzoneServerFiles" method="POST" enctype="multipart/form-data">
@csrf
    <div class="dz-message needsclick">
        {{ __('drop_files_here') }}
    </div>
</form>
