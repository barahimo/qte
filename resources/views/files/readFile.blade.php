@if(in_array($file->type,['png','jpg','jpeg']))

<link rel="stylesheet" type="text/css" href="{{asset('zoom/css/zoom.css')}}" media="screen" />
<link rel="stylesheet" type="text/css" href="{{asset('zoom/css/evenZoom.css')}}" />
<script src="{{ asset('zoom/js/jquery.js') }}"></script>
<script type="text/javascript" src="{{asset('zoom/js/evenZoom.js')}}"></script>
<div id="main">
    <div class="row">
        <div id="example" data-zoomed="{{Storage::url($file->path)}}">
            <img src="{{Storage::url($file->path)}}" alt="image" />
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $("#example").evenZoom();
    });
</script>

@elseif(in_array($file->type,['mp3']))
<audio controls src="{{Storage::url($file->path)}}">
    Your browser does not support the <code>audio</code> element.
</audio>
@elseif(in_array($file->type,['mp4']))
<video controls src="{{Storage::url($file->path)}}">
    Your browser does not support the <code>video</code> element.
</video>
@elseif(in_array($file->type,['pdf']))
<embed src="{{Storage::url($file->path)}}" type="application/pdf" width="100%" height="600px" />
@elseif(in_array($file->type,['doc','docx','ppt','pptx','xls','xlsx']))
<!-- https://docs.google.com/viewer?url= -->
<iframe width="100%" height="100%" src="{{Storage::url($file->path)}}&embedded=true" frameborder=" 0" allowfullscreen></iframe>
@endif