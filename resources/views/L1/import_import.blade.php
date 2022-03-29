@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    {{ $rodeo->name }} - Rodeo #{{ $rodeo->id }}
    <hr>

    <form method="post" id="form" action="{{ route('L1.import.process', $rodeo) }}">
        @csrf
        <label style="font-weight-bold"> Day </label> <br>
        @foreach( $dates as $date )
            <label> <input type="radio" name="day" value="{{ $date }}" required> {{ $date->format('l, M d, Y') }} </label> <br>
        @endforeach
    </form>

    <div>

        <fieldset class="border border-grey px-4 my-3 text-secondary" id="column-input">
          <legend style="font-size: 1rem;">Columns</legend>
          <i class="muted">
            Your csv data must have last name, first name, group, event. <br>
            If the columns are in a different order you can specify that here. <br>
          </i>

          <table>
            <thead class="muted">
              <tr>
                <th></th>
                <th>Column number</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="pr-2">Last Name</td>
                <td>
                  <input type="text" name="lastname" value="1" id="lastname-key" onchange="validateKeyInput(this.value)" />
                </td>
              </tr>

              <tr>
                <td class="pr-2">First Name</td>
                <td>
                  <input type="text" name="firstname" value="2" id="firstname-key" onchange="validateKeyInput(this.value)" />
                </td>
              </tr>

              <tr>
                <td class="pr-2">Group </td>
                <td>
                  <input type="text" name="group" value="3" id="group-key" onchange="validateKeyInput(this.value)" />
                </td>
              </tr>

              <tr>
                <td class="pr-2">Event</td>
                <td>
                  <input type="text" name="event" value="4" id="event-key" onchange="validateKeyInput(this.value)" />
                </td>
              </tr>

              <tr>
                <td class="pr-2">Comments <span class="muted">(optional)</span></td>
                <td>
                  <input type="text" name="comments" value="5" id="comments-key" onchange="validateKeyInput(this.value)" />
                </td>
              </tr>
            </tbody>
          </table>
        </fieldset>    
    </div>
    <div id="data-input-display">
        <label>Paste data to import</label>

        <textarea id="data-input" class="form-control mb-3" rows="20"></textarea>
        <button class="btn btn-primary" onclick="process()">Validate</button>
    </div>

    <div id="summary-display" style="display: none;">
      <div class="border border-grey rounded p-3 mb-2" id="summary">
      </div>
      <button class="btn btn-primary" onclick="submitData()" id="submit-button">Submit</button>
      <button class="btn btn-secondary" onclick="change()" id="change-button">Change</button>
    </div>

</div>
@endsection

@push('body')
<script>
var form = null;
var columnInputDisplay = null;
var dataInputDisplay = null;
var dataInput = null;
var summaryDisplay = null;
var summary = null;
var submitButton = null;
var changeButton = null;
var lastnameKeyInput = null;
var firstnameKeyInput = null;
var groupKeyInput = null;
var eventKeyInput = null;
var commentsKeyInput = null;

var groups = {!! json_encode( $groups ) !!};
var events = {!! json_encode( $events ) !!};
var names = {!! json_encode( $names ) !!};
var entries = [];

window.onload = function() {
  form = document.getElementById('form');
  columnInputDisplay = document.getElementById('column-input');
  dataInputDisplay = document.getElementById('data-input-display');
  dataInput = document.getElementById('data-input');
  summaryDisplay = document.getElementById('summary-display');
  summary = document.getElementById('summary');
  submitButton = document.getElementById('submit-button');
  changeButton = document.getElementById('change-button');
  lastnameKeyInput = document.getElementById('lastname-key');
  firstnameKeyInput = document.getElementById('firstname-key');
  groupKeyInput = document.getElementById('group-key');
  eventKeyInput = document.getElementById('event-key');
  commentsKeyInput = document.getElementById('comments-key');
}

function validateKeyInput(value) {
  if( value && !validateKey(value) ) {
    alert('Invalid column number!')
  }
}

function validateKey(value) {
  return ( /^[0-9]*$/.test(value) && value > 0 );
}

function process() {
  var data = dataInput.value.trim();

  if( !data ) {
    alert("Missing data!")
    change();
    return;
  }

  // show summary, hide input... 
  columnInputDisplay.style.display = "none";
  dataInputDisplay.style.display = "none";
  summaryDisplay.style.display = "block";
  summary.innerHTML = null;
  submitButton.style.display = 'none';

  // get column keys
  var keys = {
    lastname: validateKey(lastnameKeyInput.value) ? (lastnameKeyInput.value - 1) : null,
    firstname: validateKey(firstnameKeyInput.value) ? (firstnameKeyInput.value - 1) : null,
    group: validateKey(groupKeyInput.value) ? (groupKeyInput.value - 1) : null,
    event: validateKey(eventKeyInput.value) ? (eventKeyInput.value - 1) : null,
    comments: validateKey(commentsKeyInput.value) ? (commentsKeyInput.value - 1) : null,
  }; 

  // show error if keys are not valid
  if( null === keys.lastname || null === keys.firstname || null === keys.group || null === keys.event ) {
    summary.innerHTML = '<span class="text-danger">Invalid columns numbers!</span>';
    return null;
  }

  // process the lines
  entries = [];
  var tableRows = [];
  var errorCount = 0;
  
  const lines = data.split('\n');

  for (var i = 0; i < lines.length; i++) {
    const line = lines[i];
    const columns = line.split(',');
    const entry = {
      lastname:  columns.length > keys.lastname  ? columns[keys.lastname].trim()  : null,
      firstname: columns.length > keys.firstname ? columns[keys.firstname].trim() : null,
      group:     columns.length > keys.group     ? columns[keys.group].trim()     : null,
      event:     columns.length > keys.event     ? columns[keys.event].trim()     : null,
      comments:  columns.length > keys.comment   ? columns[keys.comments].trim()  : null
    };

    var errors = validateEntry(entry);

    if(errors.length > 0) {
      errorCount++;
      var msg = errors[0];
      var row = '<tr><td class="text-danger">&times;</td><td>' + line +' <hr class="my-1"> <span class="text-danger">' + msg + '</span></td></tr>';
      tableRows.push(row);
    }
    else {
      var title = JSON.stringify(entry);
      var row = '<tr><td class="text-success">&#10004;</td><td><span class="entry-line">' + line +'</span> <span class="entry-json"><hr class="my-1">' + title +'</span></td></tr>'
      tableRows.push(row);
      entries.push(entry);
    } 
  }

  // show summary
  summary.innerHTML = '';
  summary.innerHTML += '<span class="text-success">' + entries.length + ' processable rows</span><br>';
  if(errorCount > 0 ) {
    summary.innerHTML += '<span class="text-danger">' + errorCount + ' errors</span><br>'
  }
  summary.innerHTML += '<table class="mt-2 table submit-summary-table">' + (tableRows.join('')) + '</table>';

  // show submit button
  if( entries.length > 0 ) {
    submitButton.style.display = 'inline';
  }
}

function validateEntry(entry) {
    var errors = [];

    if( !entry.lastname ) {
      errors.push('Missing lastname');
    }
    
    if( !entry.firstname ) {
      errors.push('Missing firstname');
    }

    if( !entry.group ) {
      errors.push('Missing group');
    }

    if( !entry.event ) {
      errors.push('Missing event');
    }

    // group exists
    if( !groups.includes(entry.group.toUpperCase()) ) {
      errors.push('Group ' +entry.group+ ' does not exist');
    }

    // event exits
    if( !events.includes(entry.event.toUpperCase()) ) {
      errors.push('Event '+entry.event+' does not exist');
    }    
    
    // competition exists
    //  ?

    // contestant exists
    //var lexicalname = (entry.lastname + ', ' + entry.firstname).toUpperCase();;
    //if( !names.includes(lexicalname) ) {
    //  errors.push('Contestant '+lexicalname+' does not exist');
    //} 

    return errors;
}

function change() {
  columnInputDisplay.style.display = "block";
  dataInputDisplay.style.display = "block";
  summaryDisplay.style.display = "none";
}

function submitData() {
  summary.innerHTML = 'Processing...';
  submitButton.disabled = true;
  changeButton.disabled = true;
  var input = document.createElement('input');
  input.value = JSON.stringify(entries);
  input.name = "data";
  input.type = "hidden";
  form.appendChild(input);
  form.submit()
}
</script>
<style>
  .entry-json {
    display: none;
  }
  .entry-line:hover + .entry-json {
    display: block;
  }
</style>
@endpush
