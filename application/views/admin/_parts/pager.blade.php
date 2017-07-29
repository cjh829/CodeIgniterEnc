              @if(!empty($paginate_info))
              <ul class="pagination pagination-sm no-margin pull-right">
                <li><a href="{{ $paginate_info['first_page_url']  }}">&laquo;</a></li>
                @for ($i = 1; $i <= $paginate_info['last_page']; $i++)
                <li><a href="{{ ($paginate_info['current_page'] != $i) ? $paginate_info['base_page_url'] . $i : 'javascript:void(0);' }}">{{ $i }}</a></li>
                @endfor
                <li><a href="{{ $paginate_info['last_page_url']  }}">&raquo;</a></li>
              </ul>
              @endif