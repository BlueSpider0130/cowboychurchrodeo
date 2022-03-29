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

        <fieldset class="border border-grey px-4 my-3 text-secondary">
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
                  <input type="text" name="lastname" value="1" />
                </td>
              </tr>

              <tr>
                <td class="pr-2">First Name</td>
                <td>
                  <input type="text" name="firstname" value="2" />
                </td>
              </tr>

              <tr>
                <td class="pr-2">Group </td>
                <td>
                  <input type="text" name="group" value="3" />
                </td>
              </tr>

              <tr>
                <td class="pr-2">Event</td>
                <td>
                  <input type="text" name="event" value="4" />
                </td>
              </tr>

              <tr>
                <td class="pr-2">Comments <span class="muted">(optional)</span></td>
                <td>
                  <input type="text" name="comments" value="5" />
                </td>
              </tr>
            </tbody>
          </table>
        </fieldset>
    </form>

    <div id="display">
        <label>Paste data to import</label>

        <textarea id="input" class="form-control mb-3" rows="20"></textarea>
        <button class="btn btn-primary" onclick="process()">Process</button>
    </div>

</div>
@endsection

@push('body')
<script>
var display = null;
var map = null;
var entries = null;
var form = null;

window.onload = function() {
    display = document.getElementById('display');
    map = {};
    entries = [];
    form = document.getElementById('form');
}

function process() {
  entries = [];
  var lines = document.getElementById('input').value;

  if (!lines) {
    alert('No input!');
    return null;
  }

  var rows = lines.split('\n');

  var strings = [];

  for (var i = 0; i < rows.length; i++) {
    var parts = rows[i].split('\t');
    if (parts.length > 1 && parts[0] && parts[1]) {
      entries.push({
        'line': rows[i],
        'name': parts[0],
        'entry': parts[1],
        'group': null,
        'event': null,
        'partner': parts.length >= 3 ? parts[2] : null
      });

      if (!strings.includes(parts[1])) {
        strings.push(parts[1]);
      }
    }
  }

  if (strings.length < 1) {
    alert('Could not parse any events from input!');
    return null;
  }

  var listEl = document.createElement('div');

  for (var i = 0; i < strings.length; i++) {
    const key = strings[i];
    var parts = key.split(' ');
    var group = parts.length >= 1 ? parts.shift() : null;
    var event = parts.join(' ');
    map[key] = {
      'group': group,
      'event': event
    };

    var rowEl = document.createElement('div');
    rowEl.setAttribute('data-key', key);

    var groupLabel = document.createElement('label');
    groupLabel.innerHTML = "Group: &nbsp; ";
    rowEl.appendChild(groupLabel);

    var groupInput = document.createElement('input');
    groupInput.value = group;
    rowEl.appendChild(groupInput);

    var eventLabel = document.createElement('label');
    eventLabel.innerHTML = " &nbsp; Event: &nbsp; ";
    rowEl.appendChild(eventLabel);

    var eventInput = document.createElement('input');
    eventInput.value = event;
    rowEl.appendChild(eventInput);

    var button = document.createElement('button');
    button.innerHTML = "Change"
    button.classList.add('btn');
    button.classList.add('btn-outline-primary');
    button.classList.add('btn-sm');
    button.classList.add('mx-2');
    rowEl.appendChild(button);

    listEl.appendChild(rowEl);

    button.addEventListener("click", function() {
      var newGroup = this.parentElement.children[1].value;
      var newEvent = this.parentElement.children[3].value;
      var key = this.parentElement.dataset.key;
      map[key] = {
        'group': newGroup,
        'event': newEvent
      };
      alert('Group / Event updated!');
    });
  }

  display.innerHTML = '<h1> The folling groups and events have been identified </h1>';
  display.appendChild(listEl);

  display.appendChild(document.createElement('hr'));
  var processButton = document.createElement('button');
  processButton.innerHTML = "Continue";
  processButton.classList.add('btn');
  processButton.classList.add('btn-primary');
  display.appendChild(processButton);

  processButton.addEventListener('click', updateEntries);
}


function updateEntries() {
  display.innerHTML = null;
  var table = document.createElement('table');
  table.classList.add('table');

  var thead = document.createElement('thead');
  var row = document.createElement('tr');

  var col = document.createElement('td');
  col.innerHTML = 'Name';
  row.appendChild(col);

  var col = document.createElement('td');
  col.innerHTML = 'Group';
  row.appendChild(col);

  var col = document.createElement('td');
  col.innerHTML = 'Event';
  row.appendChild(col);

  var col = document.createElement('td');
  col.innerHTML = 'Partner';
  row.appendChild(col);

  thead.appendChild(row);
  table.appendChild(thead);

  var tbody = document.createElement('tbody');

  for (var i = 0; i < entries.length; i++) {
    var key = entries[i].entry;
    entries[i].group = map[key].group;
    entries[i].event = map[key].event;

    var row = document.createElement('tr');

    var col = document.createElement('td');
    col.innerHTML = entries[i].name;
    row.appendChild(col);

    var col = document.createElement('td');
    col.innerHTML = entries[i].group;
    row.appendChild(col);

    var col = document.createElement('td');
    col.innerHTML = entries[i].event;
    row.appendChild(col);

    var col = document.createElement('td');
    col.innerHTML = entries[i].partner;
    row.appendChild(col);

    tbody.appendChild(row);
  }

  table.appendChild(tbody);

  display.appendChild(table);

  var submitButton = document.createElement('button');
  submitButton.innerHTML = "Submit";
  submitButton.classList.add('btn');
  submitButton.classList.add('btn-primary');

  display.appendChild(document.createElement('hr'));
  display.appendChild(submitButton);

  submitButton.addEventListener('click', submitData);

}

function submitData() {
  display.innerHTML = 'Processing...';
  var input = document.createElement('input');
  input.value = JSON.stringify(entries);
  input.name = "data";
  input.type = "hidden";
  form.appendChild(input);
  form.submit()
}
</script>
@endpush
