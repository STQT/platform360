






<krpano version="1.19" title="Virtual Tour" showerrors="true" >
    <include url="/skin/vtourskin_admin.xml" />

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
                   tooltips_thumbs="true"
                   tooltips_hotspots="true"
                   tooltips_mapspots="true"
                   deeplinking="true"
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

    <action name="movecamera" autorun="">
        set(view.stereographic,true);
        set(view.hlookat, {{$location->hlookat ?? 0}});
    </action>

    <events name="videos" keep="true" onxmlcomplete="movecamera();" />

    <action name="startup" autorun="onstart">
        if(startscene === null OR !scene[get(startscene)], copy(startscene,scene[0].name); );
        loadscene(get(startscene), null, MERGE);
        if(startactions !== null, startactions() );
    </action>



    <scene name="scene1" title="scene1" onstart="" thumburl="/storage/panoramas/unpacked/{{ $location->folderName() }}/thumb.jpg" lat="" lng="" heading="">

{!!$location->xmllocation!!}






    </scene>
</krpano>
