<krpano>

    <action name="startup" autorun="onstart">

        if(device.panovideosupport == false,
        error('Sorry, but panoramic videos are not supported by your current browser!');
        ,
        loadscene(scene2);
        );
    </action>

    @if($location->video)

    <scene name="scene2" title="">

        <!-- include the videoplayer interface / skin (with VR support) -->
        <include url="{{asset("/skin/videointerface.xml")}}" />

        <!-- include the videoplayer plugin -->
        <plugin name="video"
                url.html5="{{asset('/plugins/videoplayer.js')}}"
                url.flash="{{asset('/plugins/videoplayer.swf')}}"
                pausedonstart="false"
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
            <!-- offer more resolutions and longer videos when showing this example online -->
            videointerface_addsource('1920x960', '{{ asset('/storage/panoramas/video/'.$location->video) }}', 'https://d8d913s460fub.cloudfront.net/krpanocloud/video/airpano/video-1024x512-poster.jpg');
            videointerface_play('1920x960');
        </action>

    </scene>
    @endif

</krpano>
