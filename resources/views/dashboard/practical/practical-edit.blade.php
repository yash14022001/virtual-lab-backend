@extends('dashboard.base') 
@section('content') 

@if(session('message'))
<div
    class="flash-message"
    id="flash-message"
    data-expires="5000"
    style="padding: 2rem;"
>
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
        {{ session("message") }}
    </p>
</div>
<!-- end .flash-message -->
@endif
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-lg-8 col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> {{ __("Edit") }}:
                        {{ $practical->title }}
                    </div>
                    <div class="card-body">
                        <form
                            method="POST"
                            action="/practical/practical/{{ $practical->id }}"
                        >
                            @csrf @method('PUT')
                            <div class="form-group row">
                                <label>Title</label>
                                <input
                                    class="form-control"
                                    type="text"
                                    placeholder="{{ __('Title') }}"
                                    name="title"
                                    value="{{ $practical->title }}"
                                    required
                                    autofocus
                                />
                            </div>

                            <div class="form-group row">
                                <label>Youtube Link</label>
                                <input
                                    type="url"
                                    class="form-control"
                                    name="youtube_link"
                                    value="{{old('url') ?? $practical->youtube_link}}"
                                />
                                @if($errors->has('url'))
                                <div class="text-danger">
                                    {{ $errors->first('url') }}
                                </div>
                                @endif
                            </div>

                            <div class="form-group row">
                                <label>University</label>
                                <select
                                    class="form-control"
                                    name="university_id"
                                    id="university-select"
                                >
                                    @foreach($universities as $university)
                                    <option
                                        value="{{ $university->id }}"
                                        @if($practical->subject->department->university->id == $university->id) selected @endif>{{ $university->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row">
                                <label>Department</label>
                                <select
                                    class="form-control"
                                    name="department_id"
                                    id="department-select"
                                >
                                    @foreach($departments as $department)
                                    <option
                                        value="{{ $department->id }}"
                                        @if($practical->subject->department->id == $department->id) selected @endif>{{ $department->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row">
                                <label>Subject</label>
                                <select
                                    class="form-control"
                                    name="subject_id"
                                    id="subject-select"
                                >
                                    @foreach($subjects as $subject)
                                    <option
                                        value="{{ $subject->id }}"
                                        @if($practical->subject->id == $subject->id) selected @endif>{{ $subject->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row">
                                <label>Old Inputs</label>
                                <table class="table table-responsive-sm table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sr. no.</th>
                                        <th>Input Name</th>
                                        <th>Input Type</th>
                                        <th>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $index = 1; ?>
                                    @foreach($practical->inputs as $input)
                                    <tr>
                                        <td>{{ $index++ }}</td>
                                        <td><strong>{{ $input->name }}</strong></td>
                                        <td>{{ $input->input->name }}</td>
                                        <td>
                                            <input type="checkbox" name="existing-input-{{$input->id}}" value="1" class='form-control '>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="form-group row">
                                <label>Old Outputs</label>
                                <table class="table table-responsive-sm table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sr. no.</th>
                                        <th>Output Name</th>
                                        <th>Output Type</th>
                                        <th>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $index = 1; ?>
                                    @foreach($practical->outputs as $output)
                                    <tr>
                                        <td>{{ $index++ }}</td>
                                        <td><strong>{{ $output->name }}</strong></td>
                                        <td>{{ $output->output->name }}</td>
                                        <td>
                                            <input type="checkbox" name="existing-output-{{$output->id}}" value="1" class='form-control '>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group row">
                                <label>New Inputs</label>
                            </div>
                            <div class="form-group row" id="input-row">
                            </div>
                            <div style="margin-bottom: 2rem;">
                              <button style="width: auto;" class="btn btn-block btn-info" type="button" id="add-input-button"><i class="cil-plus"></i></button>
                            </div>

                            <div class="form-group row">
                                <label>New Outputs</label>
                            </div>
                            <div class="form-group row" id="output-row">
                                
                            </div>
                            <div style="margin-bottom: 2rem;">
                              <button style="width: auto;" class="btn btn-block btn-info" type="button" id="add-output-button"><i class="cil-plus"></i></button>
                            </div>

                            <button
                                class="btn btn-block btn-success"
                                type="submit"
                            >
                                {{ __("Save") }}
                            </button>
                            <a
                                href="{{ route('practical.index') }}"
                                class="btn btn-block btn-primary"
                                >{{ __("Return") }}</a
                            >
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="display:none; width: 100%; column-gap: 1rem; margin-bottom: 1rem;" id="input_flex">
    <input style="flex-grow: 1;" class="form-control" type="text" placeholder="{{ __('Name of input') }}" required>
    <select class="form-control">
    @foreach($inputs as $input)
        <option value="{{ $input->id }}">{{ $input->name }}</option>
    @endforeach            
    </select>
    <button class="btn btn-block btn-danger" type="button" onclick="deleteInputRow(this)">{{ __('Delete') }}</button>
</div>

<div style="display:none; width: 100%; column-gap: 1rem; margin-bottom: 1rem;" id="output_flex">
    <input style="flex-grow: 1;" class="form-control" type="text" placeholder="{{ __('Name of output') }}" required>
    <select class="form-control">
    @foreach($outputs as $output)
        <option value="{{ $output->id }}">{{ $output->name }}</option>
    @endforeach            
    </select>
    <button class="btn btn-block btn-danger" type="button" onclick="deleteOutputRow(this)">{{ __('Delete') }}</button>
</div>

@endsection @section('javascript')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
    setTimeout(function() {
        $("#flash-message").fadeOut("fast");
    }, 3000);

    var inputCnt = 0;
    var outputCnt = 0;

    //   $( document ).ready(function() {
    //     populateDepartmentSelect('department-select', $("#university-select").val());
    //   });

    $("#university-select").change(function() {
        var id = $(this).val();
        console.log("Called");
        populateDepartmentSelect("department-select", id);
    });

    $("#department-select").change(function() {
        var id = $(this).val();
        console.log("Called");
        populateSubjectSelect("subject-select", id);
    });

    function populateDepartmentSelect(departmentSelectId, universityId) {
        $.ajax({
            url: '{{route("university.getalldepartments") }}',
            type: "POST",
            data: {
                university_id: universityId,
                _token: "{{ csrf_token() }}"
            },
            success: function(result) {
                let htmlString = "";
                if (result.status != 200) return;
                let data = result.data;
                for (let i = 0; i < data.length; i++) {
                    htmlString += `<option value='${data[i].id}'>${data[i].name}</option>`;
                }
                console.log(htmlString);
                $(`#${departmentSelectId}`).html(htmlString);
                selectFromDropDown(departmentSelectId, data[0].name);
                populateSubjectSelect(
                    "subject-select",
                    $("#department-select").val()
                );
            }
        });
    }

    function populateSubjectSelect(subjectSelectId, departmentId) {
        $.ajax({
            url: '{{route("department.getallsubjects") }}',
            type: "POST",
            data: {
                department_id: departmentId,
                _token: "{{ csrf_token() }}"
            },
            success: function(result) {
                let htmlString = "";
                if (result.status != 200) return;
                let data = result.data;
                for (let i = 0; i < data.length; i++) {
                    htmlString += `<option value='${data[i].id}'>${data[i].name}</option>`;
                }
                console.log(htmlString);
                $(`#${subjectSelectId}`).html(htmlString);
                selectFromDropDown(subjectSelectId, data[0].name);
            }
        });
    }

    function selectFromDropDown(departmentSelectId, valueToSet) {
        let selectObj = document.getElementById(departmentSelectId);
        // let valueToSet = $(this).attr('data-department');
        for (var i = 0; i < selectObj.options.length; i++) {
            if (selectObj.options[i].text == valueToSet) {
                selectObj.options[i].selected = true;
                break;
            }
        }
    }

    $('#add-input-button').click(function () {
      let cnt = inputCnt++;
      let newInputRowId = `input_flex_${cnt}`
      
      var itm = document.getElementById("input_flex");
      var cln = itm.cloneNode(true);
      cln.id = newInputRowId;
      cln.style.display = "flex";
      
      document.getElementById("input-row").appendChild(cln);
      
      $(`#${newInputRowId} select`).attr('name', `input-drop-down-${cnt}`);
      $(`#${newInputRowId} input`).attr('name', `input-name-${cnt}`);
      $(`#${newInputRowId} button`).attr('id', `input-button-${cnt}`);

  });

  $('#add-output-button').click(function () {
      let cnt = outputCnt++;
      let newOutputRowId = `output_flex_${cnt}`
      
      var itm = document.getElementById("output_flex");
      var cln = itm.cloneNode(true);
      cln.id = newOutputRowId;
      cln.style.display = "flex";
      
      document.getElementById("output-row").appendChild(cln);
      
      $(`#${newOutputRowId} select`).attr('name', `output-drop-down-${cnt}`);
      $(`#${newOutputRowId} input`).attr('name', `output-name-${cnt}`);
      $(`#${newOutputRowId} button`).attr('id', `output-button-${cnt}`);

  });

  function deleteInputRow(event) {
    console.log("Removing ", `#input_flex_${event.id.split('-')[0]}`);
    $(`#input_flex_${event.id.split('-').slice(-1)[0]}`).remove();
  }

  function deleteOutputRow(event) {
    console.log("Removing ", `#output_flex_${event.id.split('-')[0]}`);
    $(`#output_flex_${event.id.split('-').slice(-1)[0]}`).remove();
  }
</script>

@endsection
