@extends('layouts.backend')

@section('content')
    <krpano>

        <action name="startup" autorun="onstart">

            if(device.panovideosupport == false,
            error('Sorry, but panoramic videos are not supported by your current browser!');
            ,
            loadscene(videopano);
            );
        </action>

        <scene name="videopano" title="krpano Panoramic Video Example">

            <!-- include the videoplayer interface / skin (with VR support) -->
            <include url="skin/videointerface.xml" />

            <!-- include the videoplayer plugin -->
            <plugin name="video"
                    url.html5="%VIEWER%/plugins/videoplayer.js"
                    url.flash="%VIEWER%/plugins/videoplayer.swf"
                    pausedonstart="true"
                    loop="true"
                    volume="1.0"
                    onloaded="add_video_sources();"
            />

            <!-- use the videoplayer plugin as panoramic image source -->
            <image>
                <sphere url="plugin:video" />
            </image>

            <!-- set the default view -->
            <view hlookat="0" vlookat="0" fovtype="DFOV" fov="130" fovmin="75" fovmax="150" distortion="0.0" />

            <action name="add_video_sources" >
                if(browser.domain == 'krpano.com',
                <!-- offer more resolutions and longer videos when showing this example online -->
                videointerface_addsource('1920x960', '{{ public_path().'/storage/panoramas/video/'.$location->video }});
                videointerface_play('1920x960');
            </action>

        </scene>

    </krpano>
@endsection