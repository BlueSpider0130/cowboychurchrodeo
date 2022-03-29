<div>
    @if( $showOperatingAs )

        <style type="text/css">
            #app {
                margin-top: 35px;
            }
            #sidebarMenu {
                margin-top: 35px;
            }
            #operating-as {
                z-index: 999999999999999999; 
                position: fixed; 
                top: 0; 
                width: 100%;   
                height: 35px;     
                padding: 5px; 
                font-family: monospace;
                font-size: 15px;   
                border-bottom: solid 1px grey;  
                background-color: #cbe5c7;
            }

        </style>

        <div id="operating-as">

            <div style="float:left; text-align: left;">
                <i class="fas fa-user-check"></i>
                Operating as {{ $user->getName() }}
                &nbsp;
                @if( $operator )
                    <a href="{{ route('admin.user.operator.end') }}"> exit </a>
                @endif
            </div>

            @if( $operator )
                <div style="float:right; text-align: right;">
                    return to:
                    <a href="{{ route('admin.user.operator.end') }}"> 
                        {{ $operator->getName() }} 
                    </a>
                </div>
            @endif
            
            <div style="clear:both"></div>

        </div>

    @endif

</div>
