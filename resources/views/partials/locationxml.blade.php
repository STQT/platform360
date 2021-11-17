<krpano version="1.19" title="Virtual Tour">
    <include url="/skin/vtourskin.xml" />

    <skin_settings maps="false"
                   maps_type="google"
                   maps_bing_api_key=""
                   maps_google_api_key=""
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
                   thumbs_text="false"
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
    </action>

    <action name="movecamera">
      set(view.stereographic,true);
      set(view.hlookat, -45.0);
tween(view.hlookat, 45.0, 2.0);        
  </action>

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
      
       

       url = screenshotcanvas.toDataURL();
       var im = document.getElementById("krpanoscreenshot");
     im.style.display = 'none';
history.pushState(null, '', '/en/step2');    
         var ipreloader = document.getElementById("loading2");
         ipreloader.style.display = 'block';

       $.ajax({
  method: 'POST',
  url: '/savescreenshot',
 
  data: {
    "_token": $('meta[name="csrf-token"]').attr('content'),
    "photo": url,
  
  },
    success: function(data) {
       ipreloader.style.display = 'none';
       im.style.display = 'block';
           im.src= '/screenshots/'+data.pngurl;
        }
  
});
       
       

       
      }
    }

  ]]></action>
    <scene name="scene1" title="scene1" onstart="" thumburl="/storage/panoramas/unpacked/{{ $location->folderName($index) }}/thumb.jpg" lat="" lng="" heading="">
        <view hlookat="0.0" vlookat="0.0" fovtype="MFOV" fov="120" maxpixelzoom="2.0" fovmin="70" fovmax="140" limitview="auto" />

        <preview url="/storage/panoramas/unpacked/{{ $location->folderName($index) }}/preview.jpg" />

        <image type="CUBE" multires="true" tilesize="512">
            <level tiledimagewidth="2560" tiledimageheight="2560">
                <cube url="/storage/panoramas/unpacked/{{ $location->folderName($index) }}/%s/l3/%v/l3_%s_%v_%h.jpg" />
            </level>
            <level tiledimagewidth="1280" tiledimageheight="1280">
                <cube url="/storage/panoramas/unpacked/{{ $location->folderName($index) }}/%s/l2/%v/l2_%s_%v_%h.jpg" />
            </level>
            <level tiledimagewidth="640" tiledimageheight="640">
                <cube url="/storage/panoramas/unpacked/{{ $location->folderName($index) }}/%s/l1/%v/l1_%s_%v_%h.jpg" />
            </level>
        </image>
    </scene>


</krpano>
