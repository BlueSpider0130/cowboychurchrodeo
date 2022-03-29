<div class="mb-3">
    <div class="form-group {{ $errors->has('position') ? 'is-invalid' : '' }}">
        <legend class="legend-reset font-weight-bold"> 
            Position
        </legend>
        
        <hr class="my-2">

        <div class="form-check">
            <input 
                class="form-check-input" 
                type="radio" 
                name="position" 
                id="header-radio" 
                value="header"
                onchange="if( this.checked ) { togglePositionBadges('header');  } else { togglePositionBadges(); }"
                required 
                @if( 'header' == old('position') ) checked @endif
            >
            <label class="form-check-label" for="header-radio">
                <span class="badge {{ 'header' == old('position') ? 'badge-header' : 'badge-outline-header' }}" id="header-radio-badge"> Header </span>
            </label>
        </div>

        <div class="form-check">
            <input 
                class="form-check-input" 
                type="radio" 
                name="position" 
                id="heeler-radio" 
                value="heeler"
                onchange="if( this.checked ) { togglePositionBadges('heeler');  } else { togglePositionBadges(); }"
                required 
                @if( 'heeler' == old('position') ) checked @endif
            >
            <label class="form-check-label" for="heeler-radio">
                <span class="badge {{ 'heeler' == old('position') ? 'badge-heeler' : 'badge-outline-heeler' }}" id="heeler-radio-badge"> Heeler </span>
            </label>
        </div>

        <div class="form-check">
            <input 
                class="form-check-input" 
                type="radio" 
                name="position" 
                id="any-radio" 
                value="0"
                onchange="togglePositionBadges();"
                required 
                @if( old()  &&  !in_array(old('position'), ['header', 'heeler']) ) checked @endif
            >
            <label class="form-check-label" for="any-radio">
                Any
            </label>
        </div>                   
    </div>
    <x-form.error name="position" />
</div>
