@php
if( !isset($competition) )
{
    $competition = new \App\Competition;
}
@endphp

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="entry_fee"> Entry fee </label>
                                        <div class="input-group {{ $errors->has('entry_fee') ? 'is-invalid' : '' }}">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">$</span>
                                            </div>
                                            <input 
                                                type="text" 
                                                name="entry_fee"
                                                value="{{ old('entry_fee', $competition->entry_fee) }}"
                                                id="entry_fee"
                                                class="form-control {{ $errors->has('entry_fee') ? 'is-invalid' : '' }}" 
                                            >
                                        </div>
                                        <x-form.error name="entry_fee" />
                                    </div>
                                </div>
                            </div>


                            <div class="form-group mb-3">     
                                @php
                                    $checked = old() 
                                                ? old('allow_multiple_entries') 
                                                : (isset($competition->id) ? $competition->allow_multiple_entries_per_contestant : false)
                                @endphp
                                <div class="custom-control custom-checkbox mb-2">
                                    <input 
                                        type="checkbox" 
                                        name="allow_multiple_entries"
                                        {{ $checked ? 'checked' : '' }}
                                        class="custom-control-input" 
                                        id="allow-multiple-entries"
                                        onchange="
                                            document.getElementById('max-entries-group').style.display = this.checked ? 'block' : 'none'; 
                                            if( !this.checked ) { document.getElementById('max-entries-input').value = ''; }
                                        "
                                    >
                                    <label class="custom-control-label" for="allow-multiple-entries"> 
                                        Allow multiple entries per contestant 
                                    </label>                                    
                                </div>
           
                                <div id="max-entries-group" style="display: {{ $checked ? 'block' : 'none' }}">
                                    <label>
                                        Max entries per contestant
                                        <span class="ml-2 text-secondary" style="font-size: .75rem">(optional)</span>
                                    </label>
                                    <div class="row">
                                        <div class="col-4">                                
                                            <x-form.input name="max_entries_per_contestant" id="max-entries-input" :value="$competition->max_entries_per_contestant" />
                                        </div>
                                    </div>
                                </div>
                            </div>


                            @if( $rodeo->starts_at  &&  $rodeo->ends_at )
                                <div class="form-group mb-3 {{ $errors->has('days') ? 'is-invalid' : '' }}">
                                    <legend class="legend-reset"> 
                                        Days 
                                    </legend>
                                    @php
                                        $day = $rodeo->starts_at->copy()->startOfDay();
                                        $lastDay = $rodeo->ends_at->copy()->endOfDay();
                                        $count = 0; 
                                        
                                        $checkedDayTimestamps = $competition->getInstanceStartTimestamps();
                                    @endphp
                                    @while( $day < $lastDay )
                                        @php                        
                                            $checked = old('days')  
                                                        ? in_array( $day->timestamp, old('days') )
                                                        : in_array($day->timestamp, $checkedDayTimestamps);
                                        @endphp
                                        <div class="custom-control custom-checkbox">
                                            <input 
                                                type="checkbox" 
                                                name="days[]"
                                                value="{{ $day->timestamp }}"
                                                {{ $checked ? 'checked' : '' }}
                                                id="day-{{ $count }}"
                                                class="custom-control-input" 
                                            >
                                            <label class="custom-control-label" for="day-{{ $count }}"> 
                                                <x-rodeo-date :date="$day" />
                                            </label>                                    
                                        </div>  
                                        @php
                                            $day->addDays(1);
                                            $count++;
                                        @endphp
                                    @endwhile
                                    @if( $errors->has("days.*") )
                                        <div class="text-danger"> The selected days are not valid. </div>
                                    @endif
                                </div>
                                @error('days')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror   
                            @endif
