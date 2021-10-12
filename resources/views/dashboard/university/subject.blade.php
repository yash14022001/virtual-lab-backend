@extends('dashboard.base')

@section('content')
    @if(session('message'))
    <div class="flash-message" id="flash-message" data-expires="5000" style="padding: 2rem;">
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{session('message')}}</p>
    </div> <!-- end .flash-message -->    
    @endif

    <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i>{{ __('Subject') }}</div>
                    <div class="card-body">
                        <div class="col-sm-12 col-md-6 col-lg-5 col-xl-4">
                            <form method="POST" action="{{ route('subject.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label>University : </label>
                                    <select class="form-control" id="university-select" name="university">
                                      @foreach ($universities as $university)
                                        <option value="{{$university->id}}">{{$university->name}}</option>
                                      @endforeach
                                    </select>
                                </div>

                                <div class="form-group row">
                                    <label>Department : </label>
                                    <select class="form-control" id="department-select" name="department">
                                    </select>
                                </div>
                                
                                <div class="form-group row">
                                    <label>Subject name : </label>
                                    <input class="form-control" type="text" placeholder="{{ __('Subject') }}" name="name" required autofocus>
                                    @if($errors->has('name'))
                                        <div class="text-danger">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                                
                                <div class="row"> 
                                    <button class="btn btn-block btn-success" type="submit">{{ __('Add Subject') }}</button>
                                </div>
                            </form>
                        </div>
                        
                        <br>
                        <table class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th style="width: 100px;">Sr. no. </th>
                            <th>Subject Name</th>
                            <th>Subject's Department</th>
                            <th>Subject's University</th>
                            <th style="width: 10rem;">Edit Subject</th>
                            <th style="width: 10rem;">Delete Subject</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php $index = $subjects->perPage() * ($subjects->currentPage() - 1) + 1; ?>
                          @foreach($subjects as $subject)
                            <tr>
                              <td>{{$index++}}</td>
                              <td><strong>{{ $subject->name }}</strong></td>
                              <td><strong>{{ $subject->department->name }}</strong></td>
                              <td><strong>{{ $subject->department->university->name }}</strong></td>
                              <td><button class="btn btn-block btn-success" data-name="{{$subject->name}}" id="edit-subject" data-attr="{{ route('subject.update', $subject->id) }}" data-university="{{ $subject->department->university->name }}" data-department="{{ $subject->department->name }}" type="button" data-toggle="modal" data-target="#edit-modal">Edit Subject</button></td>
                              <td>
                                <form action="{{ route('subject.destroy', $subject->id ) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-block btn-danger">Delete</button>
                                </form>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                      {{ $subjects->links() }}
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Subject</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-form" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="form-group row">
                                <label>University : </label>
                                <select class="form-control" id="university-select-modal" name="university">
                                  @foreach ($universities as $university)
                                    <option value="{{$university->id}}">{{$university->name}}</option>
                                  @endforeach
                                </select>
                            </div>

                            <div class="form-group row">
                                <label>Department : </label>
                                <select class="form-control" id="department-select-modal" name="department">
                                </select>
                            </div>

                            <div class="form-group row">
                              <label>Subject name : </label>
                              <input class="form-control" type="text" placeholder="{{ __('Subject') }}" id="modal-subject-name" name="name" required autofocus>
                              @if($errors->has('name'))
                                  <div class="text-danger">{{ $errors->first('name') }}</div>
                              @endif
                            </div>
                            
                            <div class="form-group row">
                              <button class="btn btn-primary" type="submit">Save changes</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
      
@endsection

@section('javascript')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
    setTimeout(function() {
        $('#flash-message').fadeOut('fast');
    }, 3000);
    
    $( document ).ready(function() {
      populateDepartmentSelect('department-select', $("#university-select").val());
    });

    $(document).on('click', '#edit-subject', function(event) {
      event.preventDefault();
      console.log("Called");
      let href = $(this).attr('data-attr');
      $("#modal-subject-name").val($(this).attr('data-name'));
      $("#edit-form").attr('action', href);

      let selectObj = document.getElementById("university-select-modal");
      let valueToSet = $(this).attr('data-university');
      let universityId = null;
      let departmentId = null;
      for (var i = 0; i < selectObj.options.length; i++) {
        if (selectObj.options[i].text== valueToSet) {
            selectObj.options[i].selected = true;
            departmentId = selectObj.options[i].value;
            break;
        }
      }

      populateDepartmentSelect("department-select-modal", departmentId, $(this).attr('data-department'));
    });

    $('#university-select').change(function () {
        var id = $(this).val();
        console.log("Called");
        populateDepartmentSelect("department-select", id);
    });

    $('#university-select-modal').change(function () {
        var id = $(this).val();
        console.log("Called");
        populateDepartmentSelect("department-select-modal", id);
    });

    function populateDepartmentSelect(departmentSelectId, universityId, valueToSetOnLoad = null) {
      $.ajax({
        url: '{{route("university.getalldepartments") }}',
        type: 'POST',
        data: {
          "university_id": universityId,
          "_token": '{{ csrf_token() }}',
        },
        success: function(result){
          let htmlString = "";
          if(result.status != 200) return;
          let data = result.data;
          for(let i = 0; i < data.length; i++) {
            htmlString += `<option value='${data[i].id}'>${data[i].name}</option>`;
          }
          console.log(htmlString);
          $(`#${departmentSelectId}`).html(htmlString);
          selectDepartment(departmentSelectId, valueToSetOnLoad || data[0].name);
        },
      });
    }

    function selectDepartment(departmentSelectId, valueToSet) {
      let selectObj = document.getElementById(departmentSelectId);
      console.log("Called to select ", valueToSet);
      // let valueToSet = $(this).attr('data-department');
      for (var i = 0; i < selectObj.options.length; i++) {
        if (selectObj.options[i].text== valueToSet) {
            selectObj.options[i].selected = true;
            break;
        }
      }
    }
 
</script>

@endsection