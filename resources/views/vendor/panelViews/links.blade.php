

<li class="s-link {{ (stripos(Request::segment(2), $value['url']) !== false)?'active':'' }}">
	<a  href="{{ url('panel/'.$value['url'].'/all') }}" class="{{ (Request::segment(2)==$value['url'])?'active':'' }}"><i class="fa fa-edit fa-fw"></i> {{$value['display']}}  </a>   
	@if (Auth::user()->groupId == 0)
	@endif
	<div class="items-bar"> <a href="{{ url('panel/'.$value['url'].'/edit') }}" class="ic-plus" title="Add" ></a> <a  title="List" class="ic-lines" href="{{ url('panel/'.$value['url'].'/all') }}" >  </a>  </div>        
</li>