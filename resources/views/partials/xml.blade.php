<krpano version="1.19" title="Virtual Tour" logkey="true" showerrors="true">
    <include url="/skin/vtourskin.xml" />
<plugin name="gyro" keep="true" url="/plugins/gyro2.js" softstart="1.0" enabled="true"  devices="html5" />
    <skin_settings maps="true"
                   maps_type="google"
                   maps_bing_api_key=""
                   maps_google_api_key="AIzaSyCuukKz6F0pH77lK5jgKdNEHa7AFqPMh4k"
                   maps_zoombuttons="false"
                   gyro="true"
                   webvr="true"
                   webvr_gyro_keeplookingdirection="false"
                   webvr_prev_next_hotspots="true"
                   littleplanetintro="false"
                   title="true"
                   thumbs="true"
                   thumbs_width="120" thumbs_height="80" thumbs_padding="10" thumbs_crop="0|40|240|160"
                   thumbs_opened="false"
                   thumbs_text="true"
                   thumbs_dragging="true"
                   thumbs_onhoverscrolling="false"
                   thumbs_scrollbuttons="false"
                   thumbs_scrollindicator="false"
                   thumbs_loop="false"
                   tooltips_buttons="false"
                   tooltips_thumbs="false"
                   tooltips_hotspots="false"
                   tooltips_mapspots="false"
                   deeplinking="false"
                   loadscene_flags="MERGE"
                   loadscene_blend="OPENBLEND(0.5, 0.0, 0.75, 0.05, linear)"
                   loadscene_blend_prev="SLIDEBLEND(0.5, 180, 0.75, linear)"
                   loadscene_blend_next="SLIDEBLEND(0.5,   0, 0.75, linear)"
                   loadingtext="loading..."
                   layout_width="100%"
                   layout_maxwidth="814"
                   controlbar_width="-24"
                   controlbar_height="40"
                   controlbar_offset="20"
                   controlbar_offset_closed="-40"
                   controlbar_overlap.no-fractionalscaling="10"
                   controlbar_overlap.fractionalscaling="0"
                   design_skin_images="vtourskin.png"
                   design_bgcolor="0x2D3E50"
                   design_bgalpha="0.8"
                   design_bgborder="0"
                   design_bgroundedge="1"
                   design_bgshadow="0 4 10 0x000000 0.3"
                   design_thumbborder_bgborder="3 0xFFFFFF 1.0"
                   design_thumbborder_padding="2"
                   design_thumbborder_bgroundedge="0"
                   design_text_css="color:#FFFFFF; font-family:Arial;"
                   design_text_shadow="1"
    />

    <action name="startup" autorun="onstart">
        if(startscene === null OR !scene[get(startscene)], copy(startscene,scene[0].name); );
        loadscene(get(startscene), null, MERGE);
        if(startactions !== null, startactions() );
        if(skin_settings.maps == true,



    </action>

    <action name="movecamera">
      set(view.stereographic,true);
      set(view.hlookat, -45.0);
tween(view.hlookat, 45.0, 2.0);
  </action>
  <action name="prevscene">

	if(%1 != findnext, sub(i,scene.count,1));
	txtadd(scenexml,'<krpano>',get(scene[%i].content),'</krpano>');
	if(scenexml == xml.content,
   	dec(i);
   	if(i LT 0, sub(i,scene.count,1));
   	loadscene(get(scene[%i].name), null, MERGE, BLEND(1));
  	,
   	dec(i);
   	if(i GE 0, prevscene(findnext));
  	);
</action>
{{-- <plugin name="soundinterface"

	        url="/plugins/soundinterface.js"
	        rootpath=""
	        preload="true"
           volume="0.7"
	        keep="true"
	        /> --}}
{{-- <events onxmlcomplete="wait(2); playsound(bg, 'https://dev2.uzbekistan360.uz/plugins/ding_dong_merrily_on_high.mp3|https://dev2.uzbekistan360.uz/plugins/ding_dong_merrily_on_high.ogg',1); "/> --}}


<style name="button" type="text"
         css="text-align:center;"
         padding="4 8"
         mergedalpha="false"
         bgborder="4 0xFFFFFF 1"
         bgroundedge="1"
         bgshadow="0 1 4 0x000000 1.0"
         onover="set(bgcolor, 0xC7E4FC);"
         onout="calc(bgcolor, pressed ? 0x90CAF9 : 0xFFFFFF);"
         ondown="set(bgcolor, 0x90CAF9);"
         onup="calc(bgcolor, hovering ? 0xC7E4FC : 0xFFFFFF);"
         />




  <action name="makescreenshot_init" type="Javascript" autorun="onstart"><![CDATA[

    // Load the FileSaver.js script for saving files locally cross browser.
    // Source: https://github.com/eligrey/FileSaver.js/
    krpano.loadFile("/FileSaver.js", function(file)
    {
      eval(file.data.replace("export ",""));
      krpano.screenshotSaveAs = saveAs;
    });


    // count the screenshots (for the filenames)
    krpano.makescreenshot_count = 1;


    // add a 'makescreenshot' action to krpano
    krpano.makescreenshot = function()
    {
      // if there is already a screenshot layer, remove it now
      krpano.call("removelayer(screenshot,true)");

      // create an empty Object as makeScreenshot cache
      krpano.makeScreenshotCache = {};

      // make a screenshot as canvas
      var sizeinfo = {w:0, h:0};
      var screenshotcanvas = krpano.webGL.makeScreenshot(400, 400, true, "canvas", 0, null, sizeinfo, krpano.makeScreenshotCache);

      if (screenshotcanvas)
      {

       var currentLocation = window.location.href;


       url = screenshotcanvas.toDataURL();
var previewlinkurlshare = document.getElementById("previewlinkurlshare");
previewlinkurlshare.value = currentLocation;
       var im = document.getElementById("krpanoscreenshot");
        im.style.display = 'none';

         var ipreloader = document.getElementById("loading2");
         ipreloader.style.display = 'block';

       $.ajax({
  method: 'POST',
  url: '/ru/savescreenshot',

  data: {
    "_token": $('meta[name="csrf-token"]').attr('content'),
    "photo": url,

  },
    success: function(data) {


           im.src= '/screenshots/'+data.pngurl;
            ipreloader.style.display = 'none';
               im.style.display = 'block';
        }

});




      }
    }

  ]]></action>
    <scene name="scene1" title="scene1" onstart="" thumburl="/storage/panoramas/unpacked/{{ $location->folderName($index) }}/thumb.jpg" lat="41.311108"  lng="69.279711" heading="">
{!!$location->xmllocation!!}

    @if ($location->videos)
      @foreach ($location->videos as $video)
      <action name="calc_pos_from_hfov_yaw_pitch_roll">
        div(hfov,%1,57.295779);
        div(yaw,%2,-57.295779);
        div(pitch,%3,57.295779);
        div(roll,%4,-57.295779);
        mul(hfov,0.5);Math.tan(hfov);mul(width,hfov,1000);set(height,'prop');
        Math.cos(ch,yaw);Math.sin(sh,yaw);
        Math.cos(ca,pitch);Math.sin(sa,pitch);
        Math.cos(cb,roll);Math.sin(sb,roll);
        mul(m0,ca,ch);
        mul(tmp,cb,sa);mul(tmp,ch);mul(tmp2,sb,sh);add(m3,tmp,tmp2);
        mul(m4,cb,ca);
        mul(tmp,cb,sa);mul(tmp,sh);mul(tmp2,sb,ch);sub(m5n,tmp,tmp2);mul(m5n,-1);
        mul(tmp,sb,sa);mul(tmp,ch);mul(tmp2,cb,sh);sub(m6n,tmp,tmp2);mul(m6n,-1);
        Math.atan2(yaw,m6n,m0);
        Math.atan2(roll,m5n,m4);
        Math.asin(pitch,m3);
        mul(ath,yaw,57.295779);
        mul(atv,pitch,57.295779);
        mul(rotate,roll,57.295779);
      </action>

      <!--  Это код для хотспота видео (значениея конечно надо менять на свои) -->
       <hotspot name="video"        
               url.flash="/plugins/videoplayer.swf"
               url.html5="/plugins/videoplayer.js"
           videourl.desktop="/storage/videos/{{ $video->video }}"
               videourl.mobile.or.tablet="/storage/videos/{{ $video->video }}"
           onloaded="calc_pos_from_hfov_yaw_pitch_roll({{ $video->hfov }}, {{ $video->yaw }}, {{ $video->pitch }}, {{ $video->roll }});"
               distorted="true"
               alpha="1"
           zorder="100"
               pausedonstart="false"
               loop="true"
                 directionalsound="true"
                 range="200"
                 volume="0.7"
                 onclick="togglepause();"
             onhover="showtext(OFF/ON)"
                  capture="false"
               
               />
        @endforeach
    @endif
    


    </scene>



</krpano>
