@extends('layouts.backend')

@section('content')
  <form action="https://dev2.uzbekistan360.uz/admin/floors/tochki/{{$floor->id}}" method="POST">
  <div class="container">
      <div class="row">
              @include('admin.sidebar')  <div class="col-md-9">
<input type="hidden" id="floorid" name="floorid" value="{{$floor->id}}" />

            <div class="col-md-12">
                <div class="card">
                      <div class="card-header">Добавить точки на {{ $floor->name }}</div>
                  <div id="wrap">
    	<div class="container-fluid">



          {{ csrf_field() }}
          <input type="hidden" name="parrentid" value="{{$floor->parrentid}}"/>
    			<div class="btn-group" data-toggle="buttons">
    				<label class="btn btn-lg btn-default active">
    					<input type="radio" name="radio-editor-mode" id="radio-editor-mode-edit" checked>Edit
    				</label>
    				<label style="display:none"class="btn btn-lg btn-default" id="radio-editor-mode-preview-label">
    					<input type="radio" name="radio-editor-mode" id="radio-editor-mode-preview">Preview
    				</label>
    				<label class="btn btn-lg btn-default" id="radio-editor-mode-jquery-label" style="display:none">
    					<input type="radio" name="radio-editor-mode" id="radio-editor-mode-jquery">jQuery Code
    				</label>
    				<label  class="btn btn-lg btn-default" id="radio-editor-mode-load-label" style="display:none">
    					<input type="radio" name="radio-editor-mode" id="radio-editor-mode-load">Load
    				</label>
    			</div>


    		<div class="panel panel-default" id="panel-editor">
    			<div class="panel-body" id="panel-canvas">
    				<div class="ndd-drawable-canvas">
    					<img src="/storage/floors/{{$floor->image}}" class="ndd-drawable-canvas-image" >
    					<div class="ndd-drawables-container"></div>
    				</div>
    			</div>
    		</div>

    		<div class="panel panel-default" id="panel-preview">
    			<div class="panel-body" id="plugin-container" style="width: 100%; height: 600px;">

    			</div>
    		</div>


    		<div class="panel panel-default" id="panel-load">
    			<div class="panel-body">
    				<textarea class="form-control" rows="10" id="textarea-load"></textarea><br>
    				<label class="btn btn-lg btn-primary" id="button-load" disabled>Load</label>
    			</div>
    		</div>


        <div class="form-group">  				<div class="well" id="well-jquery"></div>
          <label class="col-md-3 control-label">Локация</label>


            <select class="form-control" name="input-title" id="input-location" disabled>
            <option selected value="">Выбрать локацию</option>

               @foreach($locations as $location)


                 <option value="{{$location->id}}" data-slug="{{$location->slug}}" data-icon="{{$location->locscats[0]->cat_icon}}">{{$location->name}}</option>



               @endforeach
             </select>

        </div>


        								<div class="form-group">
        									<label class="col-md-3 control-label">Удалить точку</label>

        									<label class="btn btn-danger" data-toggle="modal" data-target="#modal-delete">Удалить</label>

        								</div>
                        <div class="form-group"><input type="submit" value="Update" id="etajupdatee" class="btn btn-primary"></div>
    		<div class="row" style="position:fixed;top:-1000px">

    			<!-- Popups -->

    			<div class="col-md-8">
    				<div class="panel panel-default">
    				<div id="panel-disabler"></div>

    					<div class="panel-heading">Annotations</div>
    					<div class="panel-body">


    							<div class="col-md-6 col-sm-6">
    								<!-- Content Type -->

    								<div class="form-group">
    									<label class="col-md-3 control-label">Content Type</label>
    									<div class="col-md-9">
    										<div class="btn-group" data-toggle="buttons">
    											<label class="btn btn-default active">
    												<input type="radio" name="radio-content-type" id="radio-content-type-text" checked>Text
    											</label>

    											<label class="btn btn-default">
    												<input type="radio" name="radio-content-type" id="radio-content-type-custom-html">Custom HTML
    											</label>
    										</div>
    									</div>
    								</div>

    								<!-- Title -->

    								<div class="form-group">
    									<label class="col-md-3 control-label">Title</label>
    									<div class="col-md-9">
    										<input type="text" class="form-control" id="input-title" placeholder="Enter title">
    									</div>
    								</div>

    								<!-- Text -->

    								<div class="form-group">
    									<label class="col-md-3 control-label">Text</label>
    									<div class="col-md-9">
    										<textarea class="form-control" rows="3" id="textarea-text"></textarea>
    									</div>
    								</div>

    								<!-- Text Color -->

    								<div class="form-group">
    									<label for="color-text-color" class="col-md-3 control-label">Text Color</label>
    									<div class="col-md-9">
    										<div class="input-group">
    											<input type="color" class="form-control" id="color-text-color" value="#ffffff">
    											<span class="input-group-addon" id="color-text-color-hex">#fff</span>
    										</div>
    									</div>
    								</div>

    								<!-- HTML -->

    								<div class="form-group">
    									<label class="col-md-3 control-label">HTML</label>
    									<div class="col-md-9">
    										<textarea class="form-control" rows="3" id="textarea-html"></textarea>
    									</div>
    								</div>

    								<!-- ID -->

    								<div class="form-group">
    									<label class="col-md-3 control-label">ID</label>
    									<div class="col-md-9">
    										<input type="text" class="form-control" id="input-id" placeholder="Enter ID">
    										<p class="help-block">Used for deep linking</p>
    									</div>
    								</div>

    								<!-- Deep linking URL -->

    								<div class="form-group">
    									<label class="col-md-3 control-label">Deep Linking URL</label>
    									<div class="col-md-9">
    										<div id="input-deep-link-url" class="well"></div>
    										<p id="input-deep-link-url-help" class="help-block"></p>
    									</div>
    								</div>

    								<!-- Delete -->

    								<div class="form-group">
    									<label class="col-md-3 control-label">Delete</label>
    									<div class="col-md-9">
    									<label class="btn btn-danger" data-toggle="modal" data-target="#modal-delete">Delete Annotation</label>
    									</div>
    								</div>
    							</div>


    							<div class="col-md-6 col-sm-6">
    								<!-- Tint Color -->

    								<div class="form-group">
    									<label for="color-tint-color" class="col-md-3 control-label">Tint Color</label>
    									<div class="col-md-9">
    										<div class="input-group">
    											<input type="color" class="form-control" id="color-tint-color" value="#000000">
    											<span class="input-group-addon" id="color-tint-color-hex">#000</span>
    										</div>
    									</div>
    								</div>

    								<!-- Style -->

    								<div class="form-group">
    									<label class="col-md-3 control-label">Style</label>
    									<div class="col-md-9">
    										<div class="btn-group btn-group-no-margin" data-toggle="buttons" id="btn-group-style-circle">
    											<label class="btn btn-default active">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-1" checked><div class="icon-in-label ndd-spot-icon icon-style-1"><div class="ndd-icon-main-element"></div><div class="ndd-icon-border-element"></div></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-2"><div class="icon-in-label ndd-spot-icon icon-style-2"><div class="ndd-icon-main-element"></div><div class="ndd-icon-border-element"></div></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-3"><div class="icon-in-label ndd-spot-icon icon-style-3"><div class="ndd-icon-main-element"></div><div class="ndd-icon-border-element"></div></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-4"><div class="icon-in-label ndd-spot-icon icon-style-4"><div class="ndd-icon-main-element"></div><div class="ndd-icon-border-element"></div></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-5"><div class="icon-in-label ndd-spot-icon icon-style-5"><img src="/flooreditor/img/annotator-pro/icon_loc_01.png"></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-6"><div class="icon-in-label ndd-spot-icon icon-style-6"><img src="/flooreditor/img/annotator-pro/icon_loc_02.png"></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-7"><div class="icon-in-label ndd-spot-icon icon-style-7"><img src="/flooreditor/img/annotator-pro/icon_loc_03.png"></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-8"><div class="icon-in-label ndd-spot-icon icon-style-8"><img src="/flooreditor/img/annotator-pro/icon_loc_04.png"></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-9"><div class="icon-in-label ndd-spot-icon icon-style-9"><img src="/flooreditor/img/annotator-pro/icon_loc_05.png"></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-10"><div class="icon-in-label ndd-spot-icon icon-style-10"><img src="/flooreditor/img/annotator-pro/icon_loc_06.png"></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-11"><div class="icon-in-label ndd-spot-icon icon-style-11"><img src="/flooreditor/img/annotator-pro/icon_loc_07.png"></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-12"><div class="icon-in-label ndd-spot-icon icon-style-12"><img src="/flooreditor/img/annotator-pro/icon_loc_08.png"></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-13"><div class="icon-in-label ndd-spot-icon icon-style-13"><img src="/flooreditor/img/annotator-pro/icon_loc_09.png"></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-0"><div style="height: 44px; line-height: 44px;">Invisible</div>
    											</label>
    										</div>

    										<div class="btn-group btn-group-no-margin" data-toggle="buttons" id="btn-group-style-rect">
    											<label class="btn btn-default active">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-rect-1" checked><div class="icon-in-label ndd-spot-icon icon-style-rect-1"><div class="ndd-icon-main-element"></div><div class="ndd-icon-border-element"></div></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-rect-2"><div class="icon-in-label ndd-spot-icon icon-style-rect-2"><div class="ndd-icon-main-element"></div><div class="ndd-icon-border-element"></div></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-rect-3"><div class="icon-in-label ndd-spot-icon icon-style-rect-3"><div class="ndd-icon-main-element"></div><div class="ndd-icon-border-element"></div></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-rect-4"><div class="icon-in-label ndd-spot-icon icon-style-rect-4"><div class="ndd-icon-main-element"></div><div class="ndd-icon-border-element"></div></div>
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-style-rect-0"><div style="height: 44px; line-height: 44px;">Invisible</div>
    											</label>
    										</div>
    									</div>
    								</div>

    								<!-- Popup Width -->

    								<div class="form-group">
    									<label class="col-md-3 control-label">Popup Width</label>
    									<div class="col-md-7">
    										<div class="input-group">
    											<input type="text" class="form-control" id="input-popup-width">
    											<span class="input-group-addon" id="input-popup-width-addon">px</span>
    										</div>
    									</div>

    									<div class="col-md-2">
    										<div class="checkbox">
    											<label>
    												<input type="checkbox" id="checkbox-popup-width-auto" checked>Auto
    											</label>
    										</div>
    									</div>
    								</div>

    								<!-- Popup Height -->

    								<div class="form-group">
    									<label class="col-md-3 control-label">Popup Height</label>
    									<div class="col-md-7">
    										<div class="input-group">
    											<input type="text" class="form-control" id="input-popup-height">
    											<span class="input-group-addon" id="input-popup-height-addon">px</span>
    										</div>
    									</div>

    									<div class="col-md-2">
    										<div class="checkbox">
    											<label>
    												<input type="checkbox" id="checkbox-popup-height-auto" checked>Auto
    											</label>
    										</div>
    									</div>
    								</div>

    								<!-- Popup Position -->

    								<div class="form-group">
    									<label for="radio-popup-position" class="col-md-3 control-label">Popup Position</label>
    									<div class="col-md-9">
    										<div class="btn-group" data-toggle="buttons">
    											<label class="btn btn-default active">
    												<input type="radio" name="radio-popup-position" id="radio-popup-position-top" checked>Top
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-position-bottom">Bottom
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-position-left">Left
    											</label>
    											<label class="btn btn-default">
    												<input type="radio" name="radio-popup-position" id="radio-popup-position-right">Right
    											</label>
    										</div>
    									</div>
    								</div>
    							</div>



    					</div>
    				</div>
    			</div>

    			<!-- Navigation -->

    			<div class="col-md-4">
    				<div class="panel panel-default">
    					<div class="panel-heading">Navigation</div>
    					<div class="panel-body">


    							<!-- Width -->

    							<div class="form-group">
    								<label class="col-md-3 control-label">Width</label>
    								<div class="col-md-7">
    									<div class="input-group">
    										<input type="text" class="form-control" id="input-width">
    										<span class="input-group-addon" id="input-width-addon">px</span>
    									</div>
    								</div>

    								<div class="col-md-2">
    									<div class="checkbox">
    										<label>
    											<input type="checkbox" id="checkbox-width-auto" checked>Auto
    										</label>
    									</div>
    								</div>
    							</div>



    							<!-- Height -->

    							<div class="form-group">
    								<label class="col-md-3 control-label">Height</label>
    								<div class="col-md-9">
    									<div class="input-group">
    										<input type="text" class="form-control" id="input-height">
    										<span class="input-group-addon" id="input-height-addon">px</span>
    									</div>
    								</div>
    							</div>

    							<!-- Max Zoom -->

    							<div class="form-group">
    								<label class="col-md-3 control-label">Max Zoom</label>
    								<div class="col-md-9">
    									<div class="btn-group" data-toggle="buttons">
    										<label class="btn btn-default active">
    											<input type="radio" name="radio-max-zoom" id="radio-max-zoom-1-1" checked>1:1
    										</label>
    										<label class="btn btn-default">
    											<input type="radio" name="radio-max-zoom" id="radio-max-zoom-1">1x
    										</label>
    										<label class="btn btn-default">
    											<input type="radio" name="radio-max-zoom" id="radio-max-zoom-2">2x
    										</label>
    										<label class="btn btn-default">
    											<input type="radio" name="radio-max-zoom" id="radio-max-zoom-3">3x
    										</label>
    										<label class="btn btn-default">
    											<input type="radio" name="radio-max-zoom" id="radio-max-zoom-4">4x
    										</label>
    										<label class="btn btn-default">
    											<input type="radio" name="radio-max-zoom" id="radio-max-zoom-custom">Custom
    										</label>
    									</div>
    								</div>
    							</div>

    							<div class="form-group">
    								<div class="col-md-offset-3 col-md-9">
    									<input type="text" class="form-control" id="input-max-zoom" value="4">
    								</div>
    							</div>

    							<!-- Show Navigator -->

    							<div class="form-group">
    								<label for="checkbox-navigator" class="col-md-3 control-label">Navigator</label>
    								<div class="col-md-9">
    									<div class="checkbox">
    										<label>
    											<input type="checkbox" id="checkbox-navigator">
    										</label>
    									</div>
    								</div>
    							</div>

    							<!-- Show Navigator Image Preview -->

    							<div class="form-group">
    								<label for="checkbox-navigator-image-preview" class="col-md-3 control-label">Navigator Image Preview</label>
    								<div class="col-md-9">
    									<div class="checkbox">
    										<label>
    											<input type="checkbox" id="checkbox-navigator-image-preview">
    										</label>
    									</div>
    								</div>
    							</div>

    							<!-- Enable Fullscreen -->

    							<div class="form-group">
    								<label for="checkbox-fullscreen" class="col-md-3 control-label">Enable Fullscreen</label>
    								<div class="col-md-9">
    									<div class="checkbox">
    										<label>
    											<input type="checkbox" id="checkbox-fullscreen">
    										</label>
    									</div>
    								</div>
    							</div>

    							<!-- Enable Rubberbanding -->

    							<div class="form-group">
    								<label for="checkbox-rubberbanding" class="col-md-3 control-label">Enable Rubberbanding</label>
    								<div class="col-md-9">
    									<div class="checkbox">
    										<label>
    											<input type="checkbox" id="checkbox-rubberbanding">
    										</label>
    									</div>
    								</div>
    							</div>


    					</div>
    				</div>
    			</div>
    		</div>

    	</div>
    </div>




    <!-- Modals -->

    <!-- Modal -->
    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Are you sure?</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal" id="delete-annotation-button">Delete</button>
          </div>
        </div>
      </div>
    </div>






                </div>
            </div>
</div></div></div></form>
@if (!empty($floor->code))
<textarea name="oldone" id="oldone" style="position:absolute;left:-1000px;">$("#the-img-tag").annotatorPro({
      {{$floor->code}}
});</textarea> @endif
@endsection
@push('styles')
  <link rel="stylesheet" type="text/css" href="/flooreditor/css/lib/bootstrap-yeti.min.css">
  <link rel="stylesheet" type="text/css" href="/flooreditor/css/annotator-pro-editor.css">
  <link rel="stylesheet" type="text/css" href="/flooreditor/css/annotator-pro.min.css">
@endpush
@push('scripts')

<script src="/flooreditor/js/lib/jquery.min.js"></script>
  <script src="/flooreditor/js/lib/bootstrap.min.js"></script>
<script src="/flooreditor/js/annotator-pro-editor.js"></script>
<script src="/flooreditor/js/annotator-pro.js"></script>
<script>




</script>

@endpush
