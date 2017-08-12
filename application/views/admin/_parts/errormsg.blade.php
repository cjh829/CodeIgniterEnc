              @if(empty($errormsg))
              &nbsp;
              @elseif (is_array($errormsg))
                @foreach($errormsg as $msg)
                  {{ $msg }}<br/>
                @endforeach
              @else
              {{ $errormsg }}
              @endif