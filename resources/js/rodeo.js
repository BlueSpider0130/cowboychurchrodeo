/**
 * Image preview for file input 
 */
window.xImageInputPreview = function( input, key ) 
{
    var previewId = 'x-image-input-preview-' + key;
    var previewEl = document.getElementById( previewId );

    var buttonId = 'x-image-button-' + key;
    var buttonEl = document.getElementById( buttonId );

    if( previewEl )
    {
        if( !input.files  ||  !input.files[0] )
        {
            previewEl.style.display = 'none';
            buttonEl.innerHTML = "Upload image";
        }

        if ( input.files  &&  input.files[0] ) 
        {
            var reader = new FileReader();

            reader.onload = function(e) {
                previewEl.src = e.target.result;
                previewEl.style.display = 'inline';
                buttonEl.innerHTML = "Change image";
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
}       


/**
 * Alert to confirm deleting item
 */
window.confirmDelete = function( id, message="Are you sure you want to delete this item?" ) {    
    if( confirm(message) )
    {                        
        if( formEl = document.getElementById(id) )
        {
            formEl.submit();
        }

        return true;
    }

    return false;
}


/**
 * Toggle header/heeler badges
 */
window.togglePositionBadges = function( position ) 
{
    var headerBadge = document.getElementById('header-radio-badge');
    var heelerBadge = document.getElementById('heeler-radio-badge');

    if( 'header' == position ) 
    {
        headerBadge.classList.remove('badge-outline-header');
        headerBadge.classList.add('badge-header');
        heelerBadge.classList.remove('badge-heeler');
        heelerBadge.classList.add('badge-outline-heeler');
    }
    else if( 'heeler' == position )
    {
        headerBadge.classList.remove('badge-header');
        headerBadge.classList.add('badge-outline-header');
        heelerBadge.classList.remove('badge-outline-heeler');
        heelerBadge.classList.add('badge-heeler');                               
    }
    else
    {
        headerBadge.classList.remove('badge-header');
        heelerBadge.classList.remove('badge-header');
        headerBadge.classList.add('badge-outline-header');
        heelerBadge.classList.add('badge-outline-heeler');                                
    }                                    
}        
