<?php
/*
    Required vars:  \SfpResults\ResultsRequest  $request 
*/
?>

<form method="get"> 

    <div class="input-group mb-3">

        <input type="text" name="{{ $request->getSearchParameterName() }}" value="{{ $request->getSearch() }}" class="form-control" aria-label="search">

        <div class="input-group-append">
            <button type="submit" class="btn btn-outline-secondary bg-light" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>

    </div>

</form>
