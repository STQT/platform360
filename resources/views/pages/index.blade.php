@extends('layouts.krpano', compact('location'))

@section('content')
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <style>
        .wrapper-panel.top {
            z-index: 100;
        }
    </style>
    <div class="loading">
        <div class="loader">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>
    <div id="root">
        <div class="root">
            <div class="root ">
                <div></div>
                <div style="opacity:0" class="currentlocationcordinates"
                     @if($location->onmap == 'on') data-lat="{{$location->lat}}" data-lng="{{$location->lng}}"
                     @else data-map="no" @endif ></div>
                <div class="searchPanel__button" style="display: none;">{{ $allTranslations['noresult'] ?? trans('uzb360.noresult')}}</div>
                @if (!isset($_GET['embed']))
                <header class="dubai360-header">
                    <div class="dubai360-header__logo-languaje dubai360-header__logo-slider"
                         onclick="location.href='/{{ Lang::locale() }}?home=1';">
                        <div><img src="/assets/360.svg" id="logouzb360uz" class="" width="100%"></div>
                        {{-- <div><img src="/assets/logo2.png" class="" width="100%"></div> --}}
                    </div>
                    <div class="dubai360-header__icons">
                        <div class="wrapper-button" id="hubviewlink"
                             @if(isset($sky) && $sky != "no") onclick="loadpano('uzbekistan:{{$sky->id}}', 0, '{{$sky->slug}}', '{{$location->id}}', '{{$location->slug}}', 'nooo', {{$sky->video ? ("'" . $sky->video . "'") : 'null'}})"@endif>
                            <span class="icon-ic_aerial wrapper-button__icon "></span>
                            <div class="dubai360-tooltip"><span>{{ $allTranslations['hubrejim'] ?? trans('uzb360.hubrejim')}}</span></div>
                        </div>
                        <div class="wrapper-button">
                            <span class="icon-ic_explore wrapper-button__icon " data-pannel="explorePannel"></span>
                            <div class="dubai360-tooltip"><span>{{$allTranslations['map'] ?? trans('uzb360.map')}}</span></div>
                        </div>
                        <div class="wrapper-button">
                            <span class="icon-ic_glass wrapper-button__icon " data-pannel="search"></span>
                            <div class="dubai360-tooltip"><span>{{$allTranslations['search'] ?? trans('uzb360.search')}}</span></div>
                        </div>
{{--                        <div class="wrapper-button">--}}
{{--                            <span class="icon-ic_comment wrapper-button__icon " data-pannel="feedbackPannel"></span>--}}
{{--                            <div class="dubai360-tooltip"><span>{{$allTranslations['feedback'] ?? trans('uzb360.feedback')}}</span></div>--}}
{{--                        </div>--}}
{{--                        <div class="wrapper-button">--}}
{{--                            <span class="icon-ic_question wrapper-button__icon " data-pannel="helpPannel"></span>--}}
{{--                            <div class="dubai360-tooltip"><span>{{$allTranslations['help'] ?? trans('uzb360.help')}}</span></div>--}}
{{--                        </div>--}}
                    </div>

                    @if (is_array($etajlocations) || is_object($etajlocations))
                        <div class="dubai360-header__aloneicon buttonetaj0 headermainetajicon">
                            <div class="wrapper-button active">
                                <span class="icon-ic_floorplan wrapper-button__icon"
                                      data-pannel="floorplanPanel"></span>
                                <div class="dubai360-tooltip">
                                    <span>{{$allTranslations['etaji'] ?? trans('uzb360.etaji')}}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="dubai360-header__aloneicon buttonetaj0 headermainetajicon" style="display:none">
                            <div class="wrapper-button active">
                                <span class="icon-ic_floorplan wrapper-button__icon"
                                      data-pannel="floorplanPanel"></span>
                                <div class="dubai360-tooltip">
                                    <span>{{$allTranslations['etaji'] ?? trans('uzb360.etaji')}}</span>
                                </div>
                            </div>
                        </div>


                    @endif
                    <div class="dubai360-header__description">
                        <div><span><span><span id="location_name">{{ $location->name }}</span></span><span
                                        style="position: fixed; visibility: hidden; top: 0px; left: 0px;">…</span></span>
                        </div>
                    </div>

                    <div class="dubai360-header__icons">
                        <!-- *********** TImberland Block *********** -->
                        <div class="input_city">
                            <div class="drop_down_city">
                                <select>
                                    @foreach($cities as $city)
                                        <option id="{{$city->id}}" data-my-var="{{ app()->getLocale() }}"
                                                @if($defaultlocation==$city->id || $location->city->id == $city->id) selected @endif>{{$city->name}}</option>

                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- **************************************** -->
                        <div class="wrapper-button">
                            <span class="icon-ic_info wrapper-button__icon " data-pannel="infoPannel"></span>
                            <div class="dubai360-tooltip"><span>{{ $allTranslations['information'] ?? trans('uzb360.information')}}</span></div>
                        </div>
                        <div class="wrapper-button" onclick="krpanoscreenshot();">
                            <span class="icon-ic_share wrapper-button__icon " data-pannel="sharePannel"></span>
                            <div class="dubai360-tooltip"><span>{{ $allTranslations['share'] ?? trans('uzb360.share')}}</span></div>
                        </div>
                        <div class="wrapper-button" id="autotourbutton" onclick="krpanoautorotate();">
                            <span class="icon-ic_autoplay wrapper-button__icon "></span>
                            <div class="dubai360-tooltip"><span>{{ $allTranslations['tourrejim'] ?? trans('uzb360.tourrejim')}}</span></div>
                        </div>
                        <div class="wrapper-button" id="ipadcity" style="display:none">
                          <span class="wrapper-button__icon" data-pannel="cityPannel">
            <svg version="1.1" id="Layer_1" class="wrappersvg" xmlns="http://www.w3.org/2000/svg"
                 xmlns:xlink="http://www.w3.org/1999/xlink"
                 viewBox="0 0 511.999 511.999" style="width: 28px;
          position: absolute;
          top: 11px;left:10px;
        " fill="#1a90d2">


        <g>
                      <path d="M207.904,468.1c-48.9-83.2-132.1-233-132.1-299.6c0-92.6,75.9-168.5,168.5-168.5s168.5,75.9,168.5,168.5
                          c0,66.6-83.2,216.4-132.1,299.6C261.704,497.6,223.104,493.2,207.904,468.1z M244.304,40.5c-70.7,0-128,57.2-128,128
                          c0,40.6,46.8,144.6,126.9,278.8c0,1,2.1,1,2.1,0c79.1-134.2,127-238.3,127-278.8C372.304,97.7,315.004,40.5,244.304,40.5z"/>
                  </g>
                  <g>
                      <path d="M244.304,226.7c-38.5,0-69.7-31.2-69.7-69.7s31.2-68.7,69.7-68.7s69.7,31.2,69.7,69.7S282.804,226.7,244.304,226.7z
                           M244.304,128.9c-15.6,0-29.1,12.5-29.1,29.1s12.5,29.1,29.1,29.1s29.1-12.5,29.1-29.1S259.904,128.9,244.304,128.9z"/>
                  </g>



            </svg>
          </span>

                        </div>
                        <div class="wrapper-button" onclick="krpanofullscreen()">
                            <span class="icon-ic_fullscreen wrapper-button__icon "></span>
                            <div class="dubai360-tooltip"><span>{{ $allTranslations['fullscreen'] ?? trans('uzb360.fullscreen')}}</span></div>
                        </div>

                        <div class="wrapper-button">
                            <span class="icon-ic_eye wrapper-button__icon " data-pannel="ProjectionsPannel"></span>
                            <div class="dubai360-tooltip"><span>{{ $allTranslations['rejimprosmotra'] ?? trans('uzb360.rejimprosmotra')}}</span></div>
                        </div>
                    </div>
                    <div class="dubai360-header__logo-languaje">
                    <span class="wrapper-button__icon" data-pannel="cityPannel" id="mobilelanguage"
                          style="display:none">

<svg
   width="20.520086"
   height="28">
  <g
     transform="scale(0.33678133)"
     style="fill:#1a90d2;fill-opacity:1"
     id="g6">
    <g
       style="fill:#1a90d2;fill-opacity:1"
       id="g4">
      <path
         class="cls-1"
         d="M 30.47,83.14 A 7.55,7.55 0 0 1 24.64,80.32 C 18.85,73.22 0,48.87 0,33.23 0,14.91 13.67,0 30.47,0 c 16.8,0 30.46,14.91 30.46,33.23 0,15.64 -18.85,40 -24.62,47.08 a 7.59,7.59 0 0 1 -5.84,2.83 z m 0,-77.47 C 16.79,5.67 5.67,18 5.67,33.23 c 0,11 12.58,30.28 23.36,43.5 a 1.83,1.83 0 0 0 2.89,0 C 42.68,63.51 55.26,44.18 55.26,33.23 55.26,18 44.14,5.67 30.47,5.67 Z"
         id="path909"
         style="fill:#1a90d2;fill-opacity:1" />
      <circle
         class="cls-1"
         cx="30.469999"
         cy="31.530001"
         r="11.24"
         id="circle911"
         style="fill:#1a90d2;fill-opacity:1" />
    </g>
  </g>
</svg>


    </span>
                            <div class="language-switcher">
                                <div class="dropdown-wrapper">
                                        <select name="select" class="dropdown">
                                            <option value="ru" id="ru" @if(Lang::locale()=='ru') selected @endif>RU</option>
                                            <option value="en" id="en" @if(Lang::locale()=='en') selected @endif>EN</option>
                                            <option value="it" id="it" @if(Lang::locale()=='it') selected @endif>IT</option>
                                        </select>
                                    <img src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jaGV2cm9uPC90aXRsZT48cG9seWdvbiBjbGFzcz0iY2xzLTEiIHBvaW50cz0iMTIgMTQgOSAxMSAxNSAxMSAxMiAxNCIvPjwvc3ZnPg=="
                                         class="dropdown-chevronImg">
                                </div>
                            </div>
                    </div>
                </header>
                @endif
                <div id="pano" style="width:100%;height:100%;"></div>
                <button type="button" id="playaudio"><img src="/assets/icons/sound-off.svg"></button>
                @if(isset($referer) && $referer && isset($location->information->back_button_from_domain) && $location->information->back_button_from_domain)
                <a href="{{$referer}}">
                    @php
                            $fontStyle = '';
                            $fontStyle .= $location->information->back_button_font ?  ('font-family: "' . $location->information->back_button_font . '";') : '';
                            $fontStyle .= $location->information->back_button_font_size ?  ('font-size: ' . $location->information->back_button_font_size . ';') : '';
                            $fontStyle .= $location->information->back_button_font_color ?  ('color: ' . $location->information->back_button_font_color . ';') : '';
                    @endphp
                    <div class="gobacktosite" style="background-color: {{ $location->information->back_button_background_color ? $location->information->back_button_background_color : 'yellow' }}">
                        <img src="{{ (isset($location->information->back_button_image) && $location->information->back_button_image) ? '/storage/locations_information/' . $location->information->back_button_image : '/assets/icons/home.svg' }}">
                        <span style="{{$fontStyle}}">Назад на сайт</span>
                    </div>
                </a>
                @endif
                <footer class="dubai360-footer">
                    <div class="wrapper-button">
                        @if(isset($sky) && $sky != "no")
                            <span class="icon-ic_aerial wrapper-button__icon " id="hubviewlink2" onclick="loadpano('uzbekistan:{{$sky->id}}', 0, '{{$sky->slug}}', '{{$location->id}}', '{{$location->slug}}', '',  {{$sky->video ? ("'" . $sky->video . "'") : 'null'}})"></span>
                        @else
                            <span class="icon-ic_aerial wrapper-button__icon " id="hubviewlink2"></span>
                        @endif
                    </div>
                    <div class="wrapper-button"><span class="icon-ic_explore wrapper-button__icon "
                                                      data-pannel="explorePannel"></span></div>
                    <div class="wrapper-button"><span class="icon-ic_info wrapper-button__icon "
                                                      data-pannel="infoPannel"></span></div>
                    <div class="wrapper-button"><span class="icon-ic_glass wrapper-button__icon "
                                                      data-pannel="search"></span></div>
                    <div class="wrapper-button"><span class="icon-ic_share wrapper-button__icon "
                                                      onclick="krpanoscreenshot();" data-pannel="sharePannel"></span>
                    </div>
                    <div class="wrapper-button"><span class=" wrapper-button__icon " data-pannel="gyroPannel">
                        <svg version="1.1" id="Layer_1" class="wrappersvg" xmlns="http://www.w3.org/2000/svg"
                             xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 511.999 511.999" style="width:25px;position: absolute; top: 12px;
                        right: 13px;" fill="#1a90d2">

                        <g>
                          <path d="M436.921,75.079C389.413,27.571,326.51,1.066,259.464,0.18C258.296,0.074,257.137,0,255.999,0s-2.297,0.074-3.465,0.18
                          C185.488,1.065,122.585,27.57,75.077,75.078C26.752,123.405,0.138,187.657,0.138,255.999s26.614,132.595,74.94,180.921
                          c47.508,47.508,110.41,74.013,177.457,74.898c1.168,0.107,2.327,0.18,3.464,0.18c1.138,0,2.297-0.074,3.465-0.18
                          c67.047-0.885,129.95-27.39,177.457-74.898c48.325-48.325,74.939-112.577,74.939-180.921
                          C511.861,187.657,485.247,123.405,436.921,75.079z M96.586,96.587c27.181-27.181,60.086-46.552,95.992-57.018
                          c-8.093,9.317-15.96,20.033-23.282,31.908c-9.339,15.146-17.425,31.562-24.196,48.919H75.865
                          C82.165,112.063,89.071,104.102,96.586,96.587z M56.486,150.813h78.373c-8.15,28.522-12.97,58.908-14.161,89.978H31.071
                          C33.176,208.987,41.865,178.465,56.486,150.813z M56.487,361.186c-14.623-27.652-23.312-58.174-25.417-89.978h89.627
                          c1.191,31.071,6.011,61.457,14.161,89.978H56.487z M96.587,415.412c-7.517-7.515-14.423-15.475-20.722-23.809h69.236
                          c6.771,17.357,14.856,33.773,24.196,48.919c7.322,11.875,15.189,22.591,23.282,31.908
                          C156.674,461.964,123.769,442.593,96.587,415.412z M240.79,475.322c-12.671-8.29-29.685-24.946-45.605-50.764
                          c-6.385-10.354-12.124-21.382-17.197-32.954h62.801V475.322z M240.79,361.186h-74.195c-8.888-28.182-14.163-58.651-15.459-89.978
                          h89.654V361.186z M240.79,240.791h-89.654c1.295-31.327,6.57-61.797,15.459-89.978h74.195V240.791z M240.79,120.395h-62.801
                          c5.073-11.572,10.812-22.6,17.197-32.954c15.919-25.818,32.934-42.475,45.605-50.764V120.395z M455.512,150.813
                          c14.623,27.653,23.311,58.174,25.416,89.978H391.3c-1.191-31.071-6.011-61.457-14.161-89.978H455.512z M415.413,96.587
                          c7.515,7.515,14.421,15.476,20.721,23.809h-69.235c-6.771-17.357-14.856-33.773-24.196-48.919
                          c-7.322-11.875-15.188-22.591-23.282-31.908C355.326,50.035,388.231,69.406,415.413,96.587z M271.208,36.677
                          c12.671,8.29,29.685,24.946,45.605,50.764c6.385,10.354,12.124,21.382,17.197,32.954h-62.801V36.677z M271.208,150.813h74.195
                          c8.889,28.182,14.164,58.653,15.459,89.978h-89.654V150.813z M360.861,271.208c-1.295,31.327-6.57,61.797-15.459,89.978h-74.195
                          v-89.978H360.861z M271.208,475.322v-83.718h62.801c-5.073,11.572-10.812,22.6-17.197,32.954
                          C300.893,450.377,283.879,467.032,271.208,475.322z M415.413,415.413c-27.182,27.181-60.086,46.551-95.992,57.018
                          c8.093-9.317,15.96-20.033,23.282-31.908c9.339-15.146,17.425-31.562,24.196-48.919h69.235
                          C429.835,399.937,422.928,407.898,415.413,415.413z M455.512,361.186h-78.373c8.15-28.521,12.971-58.907,14.161-89.978h89.627
                          C478.822,303.012,470.133,333.534,455.512,361.186z"/>
                      </g>

                  </svg>
                    </div>


                    @if (is_array($etajlocations) || is_object($etajlocations))
                        <div class="dubai360-header__aloneicon buttonetaj0">
                            <div class="wrapper-button active">
                                <span class="icon-ic_floorplan wrapper-button__icon"
                                      data-pannel="floorplanPanel"></span>
                                <div class="dubai360-tooltip">
                                    <span>{{ $allTranslations['etaji'] ?? trans('uzb360.etaji')}}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="dubai360-header__aloneicon buttonetaj0" style="display:none">
                            <div class="wrapper-button active">
                                <span class="icon-ic_floorplan wrapper-button__icon"
                                      data-pannel="floorplanPanel"></span>
                                <div class="dubai360-tooltip">
                                    <span>{{ $allTranslations['etaji'] ?? trans('uzb360.etaji')}}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </footer>


                <div class="wrapper-panel  top right sharePannel hidden expand">
                    <img class="wrapper-panel-close"
                         src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                    <div class="sharePanel">
                        <div class="sharePanel__title"><span>{{$allTranslations['share'] ?? trans('uzb360.share')}}</span></div>

                        <div class="sharePanel__screenshot">
                            <div class="loading2" id="loading2">
                                <div class="loader2">
                                    <div class="dot2"></div>
                                    <div class="dot2"></div>
                                    <div class="dot2"></div>
                                </div>
                            </div>
                            <img src="" id="krpanoscreenshot" style="display: none"></div>
                        <div class="sharePanel__social">
                            <ul class="sharePanel__social__icons">
                                <li class="socialnetwork-icon" onclick="uzbsharefb()">
                                    <div role="button" tabindex="0"
                                         class="SocialMediaShareButton SocialMediaShareButton--facebook">
                                        <div style="width: 40px; height: 40px;">
                                            <img src="/storage/socialnetworks/facebook.png" alt="facebook share"/>
                                        </div>
                                    </div>
                                </li>
                                <li class="socialnetwork-icon" onclick="uzbsharetg()">
                                    <div role="button" tabindex="0"
                                         class="SocialMediaShareButton SocialMediaShareButton--telegram">
                                        <div style="width: 40px; height: 40px;">
                                            <img src="/storage/socialnetworks/telegram.png" alt="telegram share"/>
                                        </div>
                                    </div>
                                </li>

                                <li class="socialnetwork-icon" onclick="uzbsharewt()">
                                    <div role="button" tabindex="0"
                                         class="SocialMediaShareButton SocialMediaShareButton--whatsapp">
                                        <div style="width: 40px; height: 40px;">
                                            <img src="/storage/socialnetworks/whatsapp.png" alt="whatsapp share"/>
                                        </div>
                                    </div>
                                </li>

                                <li class="socialnetwork-icon" onclick="uzbshareVK()">
                                    <div role="button" tabindex="0"
                                         class="SocialMediaShareButton SocialMediaShareButton--vk">
                                        <div style="width: 40px; height: 40px;">
                                            <img src="/storage/socialnetworks/vkontakte.png" alt="vk share"/>
                                        </div>
                                    </div>
                                </li>

                                <li class="socialnetwork-icon" onclick="uzbshareTwitter()">
                                    <div role="button" tabindex="0"
                                         class="SocialMediaShareButton SocialMediaShareButton--twitter">
                                        <div style="width: 40px; height: 40px;">
                                            <img src="/storage/socialnetworks/twitter.png" alt="twitter share"/>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class="sharePanel__social__share">
                                <div class="sharePanel__social__share__url"><img
                                            src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4NCjxzdmcgd2lkdGg9IjE3cHgiIGhlaWdodD0iMTdweCIgdmlld0JveD0iMCAwIDE3IDE3IiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPg0KICAgIDwhLS0gR2VuZXJhdG9yOiBTa2V0Y2ggNDguMiAoNDczMjcpIC0gaHR0cDovL3d3dy5ib2hlbWlhbmNvZGluZy5jb20vc2tldGNoIC0tPg0KICAgIDx0aXRsZT5JY29ucy9pY19saW5rPC90aXRsZT4NCiAgICA8ZGVzYz5DcmVhdGVkIHdpdGggU2tldGNoLjwvZGVzYz4NCiAgICA8ZGVmcz48L2RlZnM+DQogICAgPGcgaWQ9IkhvbWVfU2hhcmUiIHN0cm9rZT0ibm9uZSIgc3Ryb2tlLXdpZHRoPSIxIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0xNTY4LjAwMDAwMCwgLTQzNS4wMDAwMDApIj4NCiAgICAgICAgPGcgaWQ9Ikdyb3VwIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxNTQ4LjAwMDAwMCwgNjQuMDAwMDAwKSIgZmlsbD0iI0ZGRkZGRiI+DQogICAgICAgICAgICA8ZyBpZD0ibGluayIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMTIuMDAwMDAwLCAzNjQuMDAwMDAwKSI+DQogICAgICAgICAgICAgICAgPGcgaWQ9Ikljb25zL2ljX2xpbmsiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDguMDAwMDAwLCA3LjAwMDAwMCkiPg0KICAgICAgICAgICAgICAgICAgICA8cGF0aCBkPSJNNS41LDE3LjAwMDMgQzQuMDMsMTcuMDAwMyAyLjY0OSwxNi40MjgzIDEuNjExLDE1LjM4OTMgQzAuNTcyLDE0LjM1MTMgMCwxMi45NjkzIDAsMTEuNTAwMyBDMCwxMC4wMzAzIDAuNTcyLDguNjQ5MyAxLjYxMSw3LjYxMTMgTDQuMTExLDUuMTExMyBMNS41MjUsNi41MjUzIEwzLjAyNSw5LjAyNTMgQzIuMzY0LDkuNjg3MyAyLDEwLjU2NTMgMiwxMS41MDAzIEMyLDEyLjQzNTMgMi4zNjQsMTMuMzEzMyAzLjAyNSwxMy45NzUzIEM0LjM0OCwxNS4yOTczIDYuNjUyLDE1LjI5NzMgNy45NzUsMTMuOTc1MyBMMTAuNDc1LDExLjQ3NTMgTDExLjg4OSwxMi44ODkzIEw5LjM4OSwxNS4zODkzIEM4LjM1MSwxNi40MjgzIDYuOTcsMTcuMDAwMyA1LjUsMTcuMDAwMyBaIE0xMC4wODU5LDUuNTAwMyBDMTAuNDc2OSw1LjEwOTMgMTEuMTA4OSw1LjEwOTMgMTEuNDk5OSw1LjUwMDMgQzExLjg5MDksNS44OTEzIDExLjg5MDksNi41MjQzIDExLjQ5OTksNi45MTQzIEw2LjkxMzksMTEuNTAwMyBDNi41MjI5LDExLjg5MTMgNS44OTA5LDExLjg5MTMgNS40OTk5LDExLjUwMDMgQzUuMTA4OSwxMS4xMDkzIDUuMTA4OSwxMC40NzczIDUuNDk5OSwxMC4wODYzIEwxMC4wODU5LDUuNTAwMyBaIE0xMi44ODg3LDExLjg4OSBMMTEuNDc0NywxMC40NzUgTDEzLjk3NDcsNy45NzUgQzE0LjYzNTcsNy4zMTQgMTQuOTk5Nyw2LjQzNSAxNC45OTk3LDUuNSBDMTQuOTk5Nyw0LjU2NSAxNC42MzU3LDMuNjg2IDEzLjk3NDcsMy4wMjUgQzEyLjY1MjcsMS43MDMgMTAuMzQ3NywxLjcwMyA5LjAyNTcsMy4wMjUgTDYuNTI1Nyw1LjUyNSBMNS4xMTE3LDQuMTExIEw3LjYxMTcsMS42MTEgQzguNjQ5NywwLjU3MiAxMC4wMzE3LDAgMTEuNDk5NywwIEMxMi45Njg3LDAgMTQuMzUwNywwLjU3MiAxNS4zODg3LDEuNjExIEMxNi40Mjc3LDIuNjUgMTYuOTk5Nyw0LjAzMSAxNi45OTk3LDUuNSBDMTYuOTk5Nyw2Ljk3IDE2LjQyNzcsOC4zNTEgMTUuMzg4Nyw5LjM4OSBMMTIuODg4NywxMS44ODkgWiIgaWQ9ImljX2xpbmsiPjwvcGF0aD4NCiAgICAgICAgICAgICAgICA8L2c+DQogICAgICAgICAgICA8L2c+DQogICAgICAgIDwvZz4NCiAgICA8L2c+DQo8L3N2Zz4=">
                                </div>
                                <input type="text" value="" id="previewlinkurlshare" readonly="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hotspotPreview-wrapper" style="display: none">
                    <div id="uzb360preview" class="hotspotPreview right" style="left: 1001.06px; top: 240.132px;">
                        <div class="hotspotPreview__innerWrapper">
                            <div class="hotspotPreview__img"><img src=""
                                                                  class="hotspotPreview__img--scene uzbhotspotimg">
                            </div>
                            <div class="hotspotPreview__icon-category"><img src=""
                                                                            class="icon-wrapper__icon--category category-normal uzbhotspoticon"
                                                                            style="background-color: rgb(237, 68, 104);">
                            </div>
                            <div class="hotspotPreview__text"></div>
                        </div>
                    </div>
                </div>
                <div class="wrapper-panel top left search {{ !$openedCategory ? 'hidden' : '' }} expand" style="z-index: 110">
                    <img class="wrapper-panel-close"
                         src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                    <div class="searchPanel">
                        <div class="searchPanel__input" id="search_adress">
                            <input type="text" class="dubai360-input search-input"
                                   placeholder="{{$allTranslations['search'] ?? trans('uzb360.search')}}">
                            <span class="clear-field" style="display: none"></span>
                        </div>
                        <div class="searchPanel__filtered ">
                        </div>
                        <div class="searchPanel__wrapper-category">
                            <div class="category-wrapper ">
                                <div class="btn_slider_slick">
                                    <span class="icon-ic_arrow_left_active_v2 icon-ic_arrow_left_active_v2_first category-wrapper--arrow is-activated--element slick-arrow slick-disabled"
                                          style="display: block;"></span>
                                    <span class="icon-ic_arrow_right__active_v2 icon-ic_arrow_right__active_v2_first category-wrapper--arrow is-activated--element slick-arrow"
                                          style="display: block;"></span></div>

                                <div class="cotegory-slick">
                                    @foreach($categories as $category)
                                        @if($loop->first or $loop->iteration % 9 == 0)
                                            <div class="category-wrapper--category">
                                                @endif

                                                <div class="icon-wrapper fade--in">
                                                    <div class="js-icon {{ (isset($openedCategory) && $openedCategory->id == $category->id) ? 'icon-wrapper--selected' : 'icon-wrapper__icon' }}"
                                                         data-category="{{ $category->id }}">
                                                        <div class="icon-wrapper__icon--category category-normal"
                                                             style="background-color:{{$category->color}}; margin-right: 10px; margin-left: 10px;">
                                                            <img src="/storage/cat_icons/{{$category->cat_icon_svg}}">
                                                        </div>
                                                        <span class="icon-wrapper__text" data-title="{{$category->name}}" data-information="{{strip_tags($category->information)}}">
                                                            @if (!empty($category->slug))
                                                                <a href="{{$category->createUrl()}}">
                                                            @endif
                                                            {{ $category->name }}
                                                            @if (!empty($category->slug))
                                                                </a>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>

                                                @if($loop->last == true or $loop->iteration % 8 == 0)
                                            </div>
                                        @endif
                                    @endforeach

                                </div>

                            </div>
                        </div>

                        @if (!isset($openedCategory))
                        <div class="searchPanel__results"><span
                                    class="color-opacity">{{$allTranslations['noresult'] ?? trans('uzb360.noresult')}}</span></div>
                        @endif
                        <!-- <div class="searchPanel__button" style="display: none;">Найдено 0 результатов</div> -->
                        <div class="searchPanel__resultscontainer">
                            <div class="virtualizedGrid__content " style="position: relative;">
                                <div style="overflow: visible; width: 0px;">
                                    <div aria-label="grid" aria-readonly="true" class="ReactVirtualized__Grid"
                                         role="grid" tabindex="0"
                                         style="box-sizing: border-box; direction: ltr; position: relative; will-change: transform;">
                                            <div class="category-name">
                                                @if (isset($openedCategory))
                                                <h1>{{$openedCategory->name}}</h1>
                                                @endif
                                            </div>
                                        <div id="searchContainer" class="ReactVirtualized__Grid__innerScrollContainer"
                                             role="rowgroup"
                                             style="position: relative; display: -webkit-flex; display: -moz-flex; display: -ms-flex; display: -o-flex; display: flex; -webkit-flex-wrap: wrap; -moz-flex-wrap: wrap; -ms-flex-wrap: wrap; -o-flex-wrap: wrap; flex-wrap: wrap;">
                                            @if (isset($openedCategory) && $openedCategoryLocations)
                                                @foreach($openedCategoryLocations->where('visibility',1)->sortBy('order') as $categoryLocation)
                                                    <div class="listItem-wrapper" onclick="loadpano('uzbekistan:{{$categoryLocation->id}}', 0, '{{$categoryLocation->slug}}', null, null, null, {{$categoryLocation->video ? "'" . $categoryLocation->video . "'" : 'null'}})">
                                                        <div class="listItem">
                                                            @php
                                                            if ($categoryLocation->video) {
                                                                $preview = 'preview/' . $categoryLocation->preview;
                                                            } else {
                                                                $preview = 'unpacked/' . $categoryLocation->folderName() . '/thumb.jpg';
                                                            }
                                                            @endphp
                                                            <div class="listItem__img"><img src="/storage/panoramas/{{$preview}}" class="listItem__img--scene"></div>
                                                            <div class="listItem__icon-category">
                                                                  <div class="icon-wrapper__icon--category category-normal" style="background-color: {{$openedCategory->color}};"><img src="/storage/cat_icons/{{$openedCategory->cat_icon_svg}}"></div>
                                                            </div>
                                                            <div class="listItem__text">
                                                            <div><span><span>{{$categoryLocation->name}}</span><span style="position: fixed; visibility: hidden; top: 0px; left: 0px;">…</span></span></div>
                                                            </div>
                                                      </div>
                                                  </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="category-information">
                                            @if (isset($openedCategory))
                                                <p>{{$openedCategory->information}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="resize-triggers">
                                    <div class="expand-trigger">
                                        <div style="width: 601px; height: 601px;"></div>
                                    </div>
                                    <div class="contract-trigger"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span>
                        <div class="kak2 sad" style="opacity: 1;">
                            <div class="fullScreenPanel fullScreenPanel_search">
                                <img class="wrapper-panel-close search-close"
                                     src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                                <div class="resultsPanel">
                                    <div class="resultsPanel__tittle"><span>{{$allTranslations['rezultatipoiska'] ?? trans('uzb360.rezultatipoiska')}}:</span></div>
                                    <div class="resultsPanel__resultsContainer" style="position: relative;">
                                        <div style="overflow: visible; height: 0px;">
                                            <div class="virtualizedGrid__content " style="position: relative;">
                                                <div style="overflow: visible;">
                                                    <div aria-label="grid" aria-readonly="true"
                                                         id="searchContainerMobile" class="ReactVirtualized__Grid"
                                                         role="grid" tabindex="0"
                                                         style="box-sizing: border-box; direction: ltr; height: 507px; position: relative; width: 372px; will-change: transform; overflow: auto;">


                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="resize-triggers">
                                                    <div class="expand-trigger">
                                                        <div style="width: 373px; height: 1px;"></div>
                                                    </div>
                                                    <div class="contract-trigger"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="resize-triggers">
                                            <div class="expand-trigger">
                                                <div style="width: 618px; height: 508px;"></div>
                                            </div>
                                            <div class="contract-trigger"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </div>
                </span>
            </div>
            <div class="wrapper-panel feedbackPannel top left hidden expand" style="z-index: 100">
                <img class="wrapper-panel-close"
                     src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                <form id="feedbackForm" class="feedbackPanel" novalidate="">
                    {{ csrf_field() }}
                    <div class="feedbackPanel__title"><span>{{$allTranslations['feedback'] ?? trans('uzb360.feedback')}}</span></div>
                    <div class="feedbackPanel__message"><span>{{$allTranslations['rasskajitenam'] ?? trans('uzb360.rasskajitenam')}}</span></div>
                    <div class="feedbackPanel__message"><p><span>По вопросам сотрудничества:<br>
                        +998971310023
                        </span></p></div>
                    <div class="feedbackPanel__wrapper-inputs">
                        <div class="feedbackPanel__wrapper-inputs--dropdown">
                            <div class="dropdown-wrapper">
                                <select name="select" id="feedbackselect" class="dropdown">
                                    <option value="BUG_REPORT">{{$allTranslations['oboshibke'] ?? trans('uzb360.oboshibke')}}</option>
                                    <option value="PRESS_ENQUIRY">{{$allTranslations['presszapros'] ?? trans('uzb360.presszapros')}}</option>
                                    <option value="FEATURE_REQUEST">{{$allTranslations['pojelaniya'] ?? trans('uzb360.pojelaniya')}}</option>
                                    <option value="NEW_CONTENT_REQUEST">{{$allTranslations['drugayatema'] ?? trans('uzb360.drugayatema')}}</option>

                                </select>
                                <img src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jaGV2cm9uPC90aXRsZT48cG9seWdvbiBjbGFzcz0iY2xzLTEiIHBvaW50cz0iMTIgMTQgOSAxMSAxNSAxMSAxMiAxNCIvPjwvc3ZnPg=="
                                     class="dropdown-chevronImg">
                            </div>
                        </div>
                    </div>
                    <div class="feedbackPanel__wrapper-inputs mail_inp">
                        <input type="email" id="mailll" name="email" class="feedbackPanel__wrapper-inputs--input"
                               placeholder="{{$allTranslations['vashemail'] ??  trans('uzb360.vashemail')}}" required>
                    </div>

                    <div class="feedbackPanel__wrapper-inputs mail_inp2">
                        <textarea name="message" class="feedbackPanel__wrapper-inputs--textarea"
                                  placeholder="{{$allTranslations['vashesoobshenie'] ??  trans('uzb360.vashesoobshenie')}}" required
                                  id="feedbacktext"></textarea>
                    </div>
                    <div class="feedbackPanel__buttons">
                            <span id="Feedbackstatus">
                            </span>

                        <button type="submit" class="send_form_btn">{{$allTranslations['otpravit'] ??  trans('uzb360.otpravit')}}</button>

                        <div class="block_thanks">
                            <div class="text_thks">
                                <p>{{$allTranslations['vashotzivprinyat'] ??  trans('uzb360.vashotzivprinyat')}}</p>
                            </div>
                            <div class="form_submit">
                                <button type="button" id="sendinggg" class="btn btn--normal">
                                    <span>OK</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="wrapper-panel  top left helpPannel hidden expand" style="z-index: 100">
                <img class="wrapper-panel-close"
                     src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                <div class="helpPanel" style="overflow: hidden;">
                    <div id="tab1" class="section-help">
                        <div class="section-help__content">
                            <span class="section-help__content__title"><span>{{ trans('help.welcome') }}</span></span>
                            <div class="section-help__content__message">
                                <div class="section-help__content__message__text">
                                    <p><span>{{ trans('help.rotate_and_scale') }}</span></p>
                                    <p><span>{{ trans('help.use_hotspots') }}</span>
                                    </p>
                                </div>
                                <img src="/assets/image_mouse.cec9e28c.png">
                            </div>
                        </div>
                    </div>
                    <div class="sitemap-block section-help">
                        <div><a href="/{{ app()->getLocale() }}/how-to-use" class="site-map">{{ trans('help.how_to_use_site') }}</a></div>
                        <div><a href="/{{ app()->getLocale() }}/sitemap" class="site-map">{{ trans('help.map_site') }}</a></div>
                    </div>
                    <div id="tab2" class="section-help" style="display: none;">
                        <div class="section-help__content">
                            <span class="section-help__content__title"><span>{{ trans('help.help') }}</span></span>
                            <div class="category-wrapper-mobile">
                                <div class="section-help__content__icons--controls">
                                    <div class="icon-wrapper">
                                        <span class="icon-ic_aerial icon-wrapper__icon--controls"></span>
                                        <div class="icon-wrapper__text"><span>{{ trans('help.go_to_city_sky') }}</span></div>
                                    </div>
                                    <div class="icon-wrapper">
                                        <span class="icon-ic_explore icon-wrapper__icon--controls"></span>
                                        <div class="icon-wrapper__text"><span>{{ trans('help.go_to_city_map') }}</span></div>
                                    </div>
                                    <div class="icon-wrapper">
                                        <span class="icon-ic_share icon-wrapper__icon--controls"></span>
                                        <div class="icon-wrapper__text"><span>{{ trans('help.share_your_view') }}</span></div>
                                    </div>
                                    <div class="icon-wrapper">
                                        <span class="icon-ic_configuration icon-wrapper__icon--controls"></span>
                                        <div class="icon-wrapper__text"><span>{{ trans('help.video_quality_speed') }}</span>
                                        </div>
                                    </div>
                                    <div class="icon-wrapper">
                                        <span class="icon-ic_comment icon-wrapper__icon--controls"></span>
                                        <div class="icon-wrapper__text"><span> {{ trans('help.leave_wish_or_feedback') }}</span>
                                        </div>
                                    </div>
                                    <div class="icon-wrapper">
                                        <span class="icon-ic_glass icon-wrapper__icon--controls"></span>
                                        <div class="icon-wrapper__text">
                                            <span>{{ trans('help.search_panoramas_by_name_or_category') }}</span></div>
                                    </div>
                                    <div class="icon-wrapper">
                                        <span class="icon-ic_info icon-wrapper__icon--controls"></span>
                                        <div class="icon-wrapper__text"><span>{{ trans('help.show_information') }}</span></div>
                                    </div>
                                    <div class="icon-wrapper">
                                        <span class="icon-ic_autoplay icon-wrapper__icon--controls"></span>
                                        <div class="icon-wrapper__text"><span>{{ trans('help.enable_automatic_tour_mode') }}</span>
                                        </div>
                                    </div>
                                    <div class="icon-wrapper">
                                        <span class="icon-ic_eye icon-wrapper__icon--controls"></span>
                                        <div class="icon-wrapper__text"><span>{{ trans('help.change_projection_view') }}</span></div>
                                    </div>
                                    <div class="icon-wrapper">
                                        <span class="icon-ic_floorplan icon-wrapper__icon--controls"></span>
                                        <div class="icon-wrapper__text"><span>{{ trans('help.view_floor_plan') }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
{{--                    <div id="tab3" class="section-help" style="display: none;">--}}
{{--                        <div class="section-help__content">--}}
{{--                            <span class="section-help__content__title"><span>Categories</span></span>--}}
{{--                            <div class="category-wrapper-mobile">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    {{--                        <div class="pagination">--}}
                    {{--                            <ul>--}}
                    {{--                                <li class="pagination__wrapper is-activated--categories" data-tab="tab1">--}}
                    {{--                                    <div><span class="item">1</span></div>--}}
                    {{--                                </li>--}}
                    {{--                                --}}{{-- <li class="pagination__wrapper" data-tab="tab2">--}}
                    {{--                                    <div><span class="item">2</span></div>--}}
                    {{--                                </li>--}}
                    {{--                                <li class="pagination__wrapper" data-tab="tab3">--}}
                    {{--                                    <div><span class="item">3</span></div>--}}
                    {{--                                </li> --}}
                    {{--                            </ul>--}}
                    {{--                        </div>--}}
                </div>
            </div>
            <div class="wrapper-panel  top right infoPannel hidden expand">
                <img class="wrapper-panel-close"
                     src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                <div class="infoPanel">
                    <div class="infoPanel__current-categories">
                        <div class="icon-wrapper__icon--category category-normal"
                             style="background-color: {{$location->categorylocation->color}}"><img
                                    src="/storage/cat_icons/{{$location->categorylocation->cat_icon_svg}}"></div>
                        <div class="clock_time">
                            <div class="infoPanel__title" id="location_name2">
                                @if (isset($openedCategory))
                                    <h2>{{ $location->name }}</h2>
                                    @else
                                    <h1>{{ $location->name }}</h1>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="time_data">
                        <div class="clock" id="vremyarabotibox">
                            <div class="clock_icon"><img src="/storage/socialnetworks/clock.png"></div>
                            <span id="vremyaraboti">{{$location->working_hours}}</span>
                        </div>

                        <div class="numberr" id="location_number_box">
                            <div class="clock_icon"><img src="/storage/socialnetworks/smartphone.png"></div>
                            <div><a id="location_number" href="tel:{{ $location->number}}">{{ $location->number}}</a>
                            </div>
                        </div>

                        <div class="numberr" id="website_box">
                            <div class="clock_icon"><img src="/storage/socialnetworks/www.png"></div>
                            <span id="website"><a href="{{ strpos($location->website, 'http://') !== false ? $location->website : 'http://' . $location->website }}"
                                                  target="_blank" rel="nofollow">{{$location->website }}</a></span>
                        </div>
                    </div>
                <!--  <div class="infoPanel__title">{{ $location->name }}</div> -->
                <!-- <div class="infoPanel__title2">{{ $location->number}}</div> -->

                    <div class="infoPanel__description">
                        <div class="infoPanel__description__message">
                            <span id="location_description">{{isset($openedCategory) && $openedCategory ? $openedCategory->information : $location->description}}</span>
                        </div>
                        <div class="dotsss">
                            <div class="svg_blockk">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                     viewBox="0 0 60 60" xml:space="preserve">
                                    <path d="M8,22c-4.411,0-8,3.589-8,8s3.589,8,8,8s8-3.589,8-8S12.411,22,8,22z"/>
                                    <path d="M52,22c-4.411,0-8,3.589-8,8s3.589,8,8,8s8-3.589,8-8S56.411,22,52,22z"/>
                                    <path d="M30,22c-4.411,0-8,3.589-8,8s3.589,8,8,8s8-3.589,8-8S34.411,22,30,22z"/>
                                    </svg>
                            </div>
                        </div>
                    </div>

                    <div class="time_data" id="location_adress_box">
                        <div class="clock">
                            <div class="clock_icon"><img src="/storage/socialnetworks/placeholder.png"></div>
                            <span id="location_adress">{{$location->address}}</span>
                        </div>
                    </div>
                    <ul class="sharePanel__social__icons" style="    width: 200px;">
                        <li class="socialnetwork-icon facebook">
                            <a href="{{strpos($location->facebook, 'http') !== false ? $location->facebook : 'https://facebook.com/' . str_replace('@', '', $location->facebook)}}" id="locationsocialfb" target="_blank" rel="nofollow">
                                <div style="width: 40px; height: 40px;">
                                    <img src="/storage/socialnetworks/facebook.png" alt="facebook share"/>
                                </div>
                            </a>
                        </li>

                        <li class="socialnetwork-icon telegram">
                            <a href="{{strpos($location->telegram, 'http') !== false ? $location->telegram : ('https://t.me/' . str_replace('@', '', $location->telegram))}}" id="locationsocialtg" target="_blank" rel="nofollow">
                                <div style="width: 40px; height: 40px;">
                                    <img src="/storage/socialnetworks/telegram.png" alt="telegram share"/>
                                </div>
                            </a>
                        </li>

                        <li class="socialnetwork-icon instagram">
                            <a href="{{strpos($location->instagram, 'http') !== false ? $location->instagram : 'https://instagram.com/' . str_replace('@', '', $location->instagram)}}" id="locationsocialig" target="_blank" rel="nofollow">
                                <div style="width: 40px; height: 40px;">
                                    <img src="/storage/socialnetworks/instagram.png" alt="whatsapp share"/>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="virtualizedGrid__otherLocation">
                        <div class="virtualizedGrid__title"><span>{{$allTranslations['drugielokasii'] ?? trans('uzb360.drugielokasii')}}</span></div>
                        <div class="virtualizedGrid__listContainer">
                            <div class="virtualizedGrid__content slick-block" style="position: relative;">
                                @foreach($otherlocations as $i=> $otherlocation)
                                    <div class="listItem-wrapper"
                                         onclick="loadpano('uzbekistan:{{$otherlocation->id}}', {{$i}}, '{{$otherlocation->slug}}', null, null, null, {{$otherlocation->video ? "'" . $otherlocation->video . "'" : 'null'}})">
                                        <div class="listItem">
                                            @if($otherlocation->preview)
                                                <div class="listItem__img">
                                                    <img src="{{$otherlocation->preview}}"
                                                            class="listItem__img--scene">
                                                </div>
                                            @else
                                                <div class="listItem__img"><img
                                                            src="/storage/panoramas/unpacked/{{$otherlocation->img}}/thumb.jpg"
                                                            class="listItem__img--scene"></div>
                                                <div class="listItem__icon-category">
                                                    <div class="icon-wrapper__icon--category category-normal"
                                                         style="background-color:{{$otherlocation->categorylocation->color}}">
                                                        <img src="/storage/cat_icons/{{$otherlocation->categorylocation->cat_icon_svg}}">
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="listItem__text">
                                                <div><span>{{$otherlocation->name}}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="infoPanel__overflow">
                                <span class="icon-ic_arrow_left_active_v2 icon-ic_arrow_left_active_v2_third arrow active"></span>
                                <span class="icon-ic_arrow_right__active_v2 icon-ic_arrow_right__active_v2_third arrow active"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wrapper-panel  top left floorplanPanel hidden expand" style="z-index: 100">
                <div class="wrapper-panel--main-container">
                    <div class="floorplan fade--in">
                        <span class="floorplan__recommended__title"><span>{{ $allTranslations['obekty'] ?? trans('uzb360.obekty')}}</span></span>
                        <div class="floorplan__listContainer">
                            <div class="virtualizedGrid__content "
                                 style="position: relative;overflow-y: scroll;overflow-x:hidden">
                                <div style="overflow: visible; height: 0px; width: 0px;">
                                    <div aria-label="grid" aria-readonly="true" class="ReactVirtualized__Grid"
                                         role="grid" tabindex="0"
                                         style="box-sizing: border-box; direction: ltr;  position: relative; width: 273px; will-change: transform; overflow: hidden auto;">
                                        <div class="ReactVirtualized__Grid__innerScrollContainer" id="multifloor-other-locations" role="rowgroup"
                                             style="width: 240px; max-width: 240px; max-height: 3900px; overflow: hidden; position: relative;">
                                            @if (is_array($etajlocations) || is_object($etajlocations))
                                                @foreach ($etajlocations as $key => $etajlocation)
                                                    <div class="listItem-wrapper" style="height: 260px;"
                                                         onclick="loadpano('uzbekistan:{{$etajlocation->id}}', {{$i}}, '{{$etajlocation->slug}}', null, null, null, '{{$etajlocation->video}}')">
                                                        <div class="listItem" style="width: 224px; height: 244px;">
                                                            <div class="listItem__img"><img
                                                                        src="/storage/panoramas/unpacked/{{$etajlocation->img}}/thumb.jpg"
                                                                        class="listItem__img--scene"></div>
                                                            <div class="listItem__icon-category">
                                                                <div class="icon-wrapper__icon--category category-normal"
                                                                     style="background-color:{{$etajlocation->categorylocation->color}}">
                                                                    <img src="/storage/cat_icons/{{$etajlocation->categorylocation->cat_icon_svg}}">
                                                                </div>
                                                            </div>
                                                            <div class="listItem__text">
                                                                <div><span>{{$etajlocation->name}}</span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="resize-triggers">
                                    <div class="expand-trigger">
                                        <div style="width: 274px; height: 547px;"></div>
                                    </div>
                                    <div class="contract-trigger"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="responsivee" style="width: calc(100% - 289px);">
                    <div class="floorplan-viewer">
                        <div class="floorplan-viewer__header">
                            <span class="floorplan-viewer__header__name">{{$location->name}}{{ isset($etaji[0]) ? (", " . $etaji[0]->name) : '' }}</span>
                            <div class="floorplan-viewer__header__actions">
                                <div class="floorplan-viewer__header__img"><span
                                            class="icon-ic_mouse floorplan-viewer__header__img--icon"></span><span
                                            class="floorplan-viewer__header__img--text"><span>{{$allTranslations['zoomkolesom'] ??  trans('uzb360.zoomkolesom')}}</span></span>
                                </div>
                                <div class="icon-wrapper"><span class="icon-ic_close close"></span></div>
                            </div>
                        </div>

                        <div class="krpano-floorplan">
                            <div id="floor-krpano" class="krpano">
                                @foreach($etaji as $i => $floor)
                                    <div id="floorplan-tab{{ $i }}" class="floorplan-tab" tabindex="-1"
                                         style="display: none;">
                                        <?php if (file_exists('storage/floors/' . $floor->image)) { ?>
                                        <div class="plan">
                                            <img class="planClass" id="floorid{{$floor->id}}" data-width="{{getimagesize('storage/floors/' . $floor->image)[0]}}" data-height="{{getimagesize('storage/floors/' . $floor->image)[1]}}"
                                                 src="/storage/floors/{{$floor->image}}">
                                            <span></span>
                                        </div>
                                        <?php } ?>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <ul class="floorplan-viewer__footer">
                            @foreach($etaji as $i => $floor)
                                <li class="floorplan-viewer__footer__element buttonetaj{{$i}}" data-tab="{{ $i }}">
                                    <img class="floorplan-viewer__footer__element__icon"
                                         src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4NCjxzdmcgd2lkdGg9IjM0cHgiIGhlaWdodD0iMzNweCIgdmlld0JveD0iMCAwIDM0IDMzIiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPg0KICAgIDwhLS0gR2VuZXJhdG9yOiBTa2V0Y2ggNDguMiAoNDczMjcpIC0gaHR0cDovL3d3dy5ib2hlbWlhbmNvZGluZy5jb20vc2tldGNoIC0tPg0KICAgIDx0aXRsZT5pY19sZXZlbDwvdGl0bGU+DQogICAgPGRlc2M+Q3JlYXRlZCB3aXRoIFNrZXRjaC48L2Rlc2M+DQogICAgPGRlZnM+PC9kZWZzPg0KICAgIDxnIGlkPSJIb21lX0Zsb29ycGxhbl92MiIgc3Ryb2tlPSJub25lIiBzdHJva2Utd2lkdGg9IjEiIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTMxMi4wMDAwMDAsIC0xMDA4LjAwMDAwMCkiPg0KICAgICAgICA8ZyBpZD0iRmxvb3JwbGFuIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSg4LjAwMDAwMCwgNjQuMDAwMDAwKSIgZmlsbD0iI0ZGRkZGRiI+DQogICAgICAgICAgICA8ZyBpZD0ibGV2ZWxzIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgyODguMDAwMDAwLCA5MzYuMDAwMDAwKSI+DQogICAgICAgICAgICAgICAgPGcgaWQ9ImxldmVsc19zZWxlY3QiPg0KICAgICAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMzMsMzguMTc0NSBDMjQuMDI3LDM4LjE3NDUgMTgsMzYuMTA2NSAxOCwzNC4xNzQ1IEMxOCwzMi40NzQ1IDIyLjY3NCwzMC42Njk1IDI5LjkxMiwzMC4yNjE1IEwzMywzMy4zNDk1IEwzNi4wODgsMzAuMjYxNSBDNDMuMzI2LDMwLjY2OTUgNDgsMzIuNDc0NSA0OCwzNC4xNzQ1IEM0OCwzNi4xMDY1IDQxLjk3MywzOC4xNzQ1IDMzLDM4LjE3NDUgTTMzLDEzLjk5OTUgQzM1LjQ4NSwxMy45OTk1IDM3LjUsMTYuMDE0NSAzNy41LDE4LjQ5OTUgQzM3LjUsMjAuOTg1NSAzNS40ODUsMjIuOTk5NSAzMywyMi45OTk1IEMzMC41MTUsMjIuOTk5NSAyOC41LDIwLjk4NTUgMjguNSwxOC40OTk1IEMyOC41LDE2LjAxNDUgMzAuNTE1LDEzLjk5OTUgMzMsMTMuOTk5NSBNMzcuOTU2LDI4LjM5MzUgTDQwLjQyNSwyNS45MjQ1IEM0NC41MiwyMS44MzA1IDQ0LjUyLDE1LjE2OTUgNDAuNDI1LDExLjA3NTUgQzM4LjQ0MSw5LjA5MjUgMzUuODA1LDcuOTk5NSAzMyw3Ljk5OTUgQzMwLjE5NSw3Ljk5OTUgMjcuNTU5LDkuMDkyNSAyNS41NzQsMTEuMDc1NSBDMjEuNDgsMTUuMTY5NSAyMS40OCwyMS44MzA1IDI1LjU3NSwyNS45MjQ1IEwyOC4wNDQsMjguMzkzNSBDMjEuNzU0LDI4Ljk1NDUgMTYsMzAuNjY0NSAxNiwzNC4xNzQ1IEMxNiwzOC42MDM1IDI1LjE1OCw0MC4xNzQ1IDMzLDQwLjE3NDUgQzQwLjg0Miw0MC4xNzQ1IDUwLDM4LjYwMzUgNTAsMzQuMTc0NSBDNTAsMzAuNjY0NSA0NC4yNDYsMjguOTU0NSAzNy45NTYsMjguMzkzNSIgaWQ9ImljX2xldmVsIj48L3BhdGg+DQogICAgICAgICAgICAgICAgPC9nPg0KICAgICAgICAgICAgPC9nPg0KICAgICAgICA8L2c+DQogICAgPC9nPg0KPC9zdmc+">
                                    <span class="floorplan-viewer__footer__element__name">{{$location->name}}, {{ $floor->name }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="wrapper-panel  top right ProjectionsPannel hidden expand">
                <div class="projection">
                    <div class="title-projection"><span>{{ $allTranslations['proektsiya'] ?? trans('uzb360.proektsiya')}}</span></div>
                    <ul>
                        <li class="selected" onclick="skin_view_normal()">
                            <span>{{ $allTranslations['pryamolineyniy'] ?? trans('uzb360.pryamolineyniy')}}</span></li>
                        <li onclick="skin_view_littleplanet()"><span>{{ $allTranslations['malenkayaplaneta'] ?? trans('uzb360.malenkayaplaneta')}}</span></li>
                        <li onclick="skin_view_fisheye()"><span>{{ $allTranslations['ribyglaz'] ?? trans('uzb360.ribyglaz')}}</span></li>
                        <li onclick="skin_view_panini()"><span>{{ $allTranslations['panini'] ?? trans('uzb360.panini')}}</span></li>
                        <li onclick="skin_view_stereographic()"><span>{{ $allTranslations['stereo'] ?? trans('uzb360.stereo')}}</span></li>
                        <li onclick="skin_view_architectural()"><span>{{ $allTranslations['archetek'] ?? trans('uzb360.archetek')}}</span></li>
                    </ul>
                </div>
            </div>
            <div class="wrapper-panel  top left gyroPannel hidden expand">
                <img class="wrapper-panel-close"
                     src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                <div class="gyro">
                    <div class="gyro__message">
                        <ul class="mobilechooselang">
                            <li><a href="#">Русский</a></li>
                            <li><a href="#">O'zbekcha</a></li>
                            <li><a href="#">English</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="wrapper-panel  top  cityPannel hidden expand" style=" width: 90%;   margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;">
                <img class="wrapper-panel-close"
                     src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                <div class="gyro">
                    <div class="input_city2">
                        <div class="drop_down_city">
                            <select id="selectsss" style="background-color:#fff">
                                @foreach($cities as $city)
                                    <option id="{{$city->id}}" data-my-var="{{ app()->getLocale() }}"
                                            @if($defaultlocation==$city->id) selected @endif>{{$city->name}}</option>

                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <span class="">
            <div class="kak2 sad" style="opacity: 1;">
              <div class="wrapper-panel explorePannel top left hidden fullScreenPanel" style="z-index: 100">
                <div class="SplitPane  horizontal "
                     style="display: flex; flex: 1 1 0%; height: 100%; position: absolute; outline: none; overflow: hidden; user-select: text; bottom: 0px; flex-direction: column; min-height: 100%; top: 0px; width: 100%;">
                  <div class="Pane horizontal Pane1  ">
                    <div style="overflow: visible; height: 100%; width: 100%;">
                      <div style="width: 100%; height: 100%;">
                        <div id="map"></div>

                        <div class="explorer-header">
                          <div class="explorer-header__icon-wrapper"><span class="icon-ic_close close"></span></div>
                        </div>

                      </div>
                    </div>
                  </div>
                    <span class="Resizer"></span>
                  <div class="resizer-block" style="position: relative;">
                    <div class="wrapper-slider">
                      <div class="wrapper-slider__slider"><span class="icon-ic_arrow_up"></span><span
                                  class="icon-ic_arrow_down"></span></div>
                    </div>
                    <div class="mybtn wrapper-button"><span>{{ $allTranslations['filtr'] ?? trans('uzb360.filtr')}}</span></div>
                  </div>
                  <div class="Pane horizontal Pane2  ">
                    <div class="explore__listContainer">
                      <div class="virtualizedGrid__otherLocation__wrapper mytab mytab1" style="height: 278px;">
                        <div class="virtualizedGrid">
                          <span class="icon-ic_arrow_left_big slick-block2_left"></span>
                          <div class="virtualizedGrid__content " style="position: relative;">
                            <div class="virtualizedGrid__otherLocation__title"><span>{{$allTranslations['izbrannielokasii'] ?? trans('uzb360.izbrannielokasii')}}</span></div>
                            <div class="ReactVirtualized__Grid slick-block2">
                                @foreach ($isfeatured as $i => $featured)
                                    <div class="listItem-wrapper featuredloctionbox" data-lat="{{$featured->lat}}"
                                         data-lng="{{$featured->lng}}"
                                         onclick="loadpano('uzbekistan:{{$featured->id}}', {{$i}}, '{{$featured->slug}}', null, null, null, '{{$featured->video}}')">
                                    <div class="listItem">
                                      <div class="listItem__img">
                                          @if ($featured->video && $featured->preview)
                                          <img src="/storage/panoramas/preview/{{$featured->img}}"
                                                  class="listItem__img--scene">
                                          @else
                                              <img src="/storage/panoramas/unpacked/{{$featured->img}}/thumb.jpg"
                                                  class="listItem__img--scene">
                                          @endif
                                      </div>
                                      <div class="listItem__icon-category">
                                        <div class="icon-wrapper__icon--category category-normal"
                                             style="background-color: {{$featured->categorylocation->color}}"><img
                                                    src="/storage/cat_icons/{{$featured->categorylocation->cat_icon_svg}}"></div>
                                      </div>
                                      <div class="listItem__text">
                                        <div><span>{{$featured->name}}</span></div>
                                      </div>
                                    </div>
                                  </div>
                                @endforeach
                            </div>
                            <div class="resize-triggers">
                              <div class="expand-trigger">
                                <div style="width: 720px; height: 276px;"></div>
                              </div>
                              <div class="contract-trigger"></div>
                            </div>
                          </div>
                          <span class="icon-ic_arrow_right_big slick-block2_right"></span>
                        </div>
                      </div>
                      <div class="virtualizedGrid__otherLocation__wrapper mytab mytab2" style="height: 278px;">
                        <div class="virtualizedGrid">
                          <span class="icon-ic_arrow_left_big slick-block3_left"></span>
                          <div class="virtualizedGrid__content " style="position: relative;">
                            <div class="virtualizedGrid__otherLocation__title"><span>{{$allTranslations['novielokasii'] ?? trans('uzb360.novielokasii')}}</span></div>
                            <div class="ReactVirtualized__Grid slick-block3">
                              @foreach ($isnew as $i => $new)
                                    <div class="listItem-wrapper featuredloctionbox" data-lat="{{$new->lat}}"
                                         data-lng="{{$new->lng}}"
                                         onclick="loadpano('uzbekistan:{{$new->id}}', {{$i}}, '{{$new->slug}}', null, null, null, '{{$new->video}}')">
                                    <div class="listItem">
                                      <div class="listItem__img">
                                          @if ($new->video && $new->preview)
                                          <img src="/storage/panoramas/preview/{{$new->img}}"
                                                  class="listItem__img--scene">
                                          @else
                                              <img src="/storage/panoramas/unpacked/{{$new->img}}/thumb.jpg"
                                                  class="listItem__img--scene">
                                          @endif
                                      </div>
                                      <div class="listItem__icon-category">
                                        <div class="icon-wrapper__icon--category category-normal"
                                             style="background-color: {{$new->categorylocation->color}}"><img
                                                    src="/storage/cat_icons/{{$new->categorylocation->cat_icon_svg}}"></div>
                                      </div>
                                      <div class="listItem__text">
                                        <div><span>{{$new->name}}</span></div>
                                      </div>
                                    </div>
                                  </div>
                                @endforeach
                            </div>
                            <div class="resize-triggers">
                              <div class="expand-trigger">
                                <div style="width: 720px; height: 276px;"></div>
                              </div>
                              <div class="contract-trigger"></div>
                            </div>
                          </div>
                          <span class="icon-ic_arrow_right_big slick-block3_right"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <span>
                  <div class="kak2 sad" style="opacity: 1;">
                    <div class="fullScreenPanel fullScreenPanel_filter">
                      <img class="wrapper-panel-close myclose"
                           src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                      <div class="filterPanel">
                        <div class="filterPanel__title">
                          <span>{{ $allTranslations['filtr'] ??  trans('uzb360.filtr')}}</span>
                        </div>
                        <ul class="filterPanel__options">
                          <li class="active" data-tab="1">
                            <span>{{ $allTranslations['izbrannielokasii'] ??  trans('uzb360.izbrannielokasii')}}</span>
                          </li>
                          <li class="" data-tab="2">
                            <span>{{ $allTranslations['novielokasii'] ??  trans('uzb360.novielokasii')}}</span>
                          </li>

                        </ul>
                      </div>
                    </div>
                  </div>
                </span>
              </div>
            </div>
          </span>
        </div>
    </div>
    </div>
    </div>

    <audio controls style="" id="audio">
        <source src="" type="audio/mpeg">
        {{ trans('uzb360.browser_not_support_audio_element') }}
    </audio>

    <div class="information-modal" id="information-modal" style="display: none">
        <div class="information-buttons"></div>
        <div class="heading">
            <div class="logo"></div>
            <div class="text">
                <div class="title"></div>
                <div class="description"></div>
            </div>
        </div>
        <div class="centered-logo"></div>
        <div class="content"></div>
        <div class="image-block"></div>
        <div class="images"></div>
    </div>
@endsection

@section('scripts')
    <script>

        let  percentHeight = 0.63, percentWidth = 0.73;

        if ($(window).height() > $(window).width()) {
            percentHeight = 0.54;
            percentWidth = 0.65;
        }
        const    myHeight = Math.floor($(".floorplanPanel  .floorplan-viewer").height() *  percentHeight) + 'px',
            myWidth = Math.floor($(".floorplanPanel  .floorplan-viewer").width() *  percentWidth) + 'px';



        @if (empty($location->number))
        $('#location_number_box').hide();
        @endif
        @if (empty($location->working_hours))
        $('#vremyarabotibox').hide();
        @endif

        @if (empty($location->address))
        $('#location_adress_box').hide();
        @endif

        @if (empty($location->website))
        $('#website_box').hide();
        @endif

        @if (!$location->audio && count($location->videos) < 1)
        $('#playaudio').hide();
        @endif
        @if ($location->videos)
        $('#playaudio').off('click');
        $('#playaudio').on('click', function () {
            @foreach ($location->videos as $vKey => $video)
            if (krpano.get('hotspot[video{{ $vKey }}].volume') == 0) {
                krpano.set('hotspot[video{{ $vKey }}].volume', '1.0');
                $('#playaudio').find('img').attr('src', '/assets/icons/sound-on.svg');
                currentVideoVolume = '1.0';
            } else {
                krpano.set('hotspot[video{{ $vKey }}].volume', '0');
                $('#playaudio').find('img').attr('src', '/assets/icons/sound-off.svg');
                currentVideoVolume = '0';
            }
            @endforeach
        });
        @endif
        @if (empty($location->facebook))
            $('.socialnetwork-icon.facebook').hide();
        @endif
        @if (empty($location->telegram))
            $('.socialnetwork-icon.telegram').hide();
        @endif
        @if (empty($location->instagram))
            $('.socialnetwork-icon.instagram').hide();
        @endif

        @foreach ($etaji as $i => $etaj)
            @if(!empty($etaj->code))
                $(".buttonetaj{{$i}}").click(function () {
                    setTimeout(function () {
                        let initHeight = $("#floorid{{$etaj->id}}").data('height');
                        let frameHeight = initHeight > 900 ? $(window).height() - 300 : initHeight;
                        let frameWidth = 'auto';
                        if (isMobile) {
                            frameHeight = $(window).height() - 300;
                        } else {
                            frameWidth = $("#floorid{{$etaj->id}}").data('width') + 'px',
                            frameHeight = frameHeight + 'px';
                        }
                        $("#floorid{{$etaj->id}}").annotatorPro({
                            maxZoom: 2,
                            navigator: false,
                            navigatorImagePreview: false,
                            frameWidth: myWidth,
                            frameHeight: myHeight,
                            // frameHeight: $(window).height() - 300,
                            // frameWidth: "auto",
                            iconsize: "15px",
                            rubberband: false,
                            // frameHeight: $(window).height() - 300,
                            fullscreen: true,
                            {!! $etaj->code !!}
                        });
                    }, 500);
                });
            @endif
        @endforeach

        var isMobile = window.matchMedia("only screen and (max-width: 767px)").matches;
        var isIpad = window.matchMedia("only screen and (min-width: 768px)").matches;

        if (isMobile) {
            var hotspotsize = "0.65";
        }
        if (isIpad) {
            var hotspotsize = "0.4";
        }
        $(window).on('popstate', function (event) {
            var pathname = window.location.pathname;
            let rootLangPattern = /\/[a-zA-Z]{2}$/;
            if (rootLangPattern.test(pathname)) {
                pathname = "/{{ app()->getLocale() }}/api/getcitydefaultlocation/{{$defaultlocation}}";
                $.get(pathname,
                    {},
                    onAjaxSuccess2
                );

                function onAjaxSuccess2(data) {
                    loadpano('uzbekistan:' + data.id + '', 0, data.slug, '', '', 'nooo', data.video);
                }
            } else {
                pathname = pathname.replace('/{{ app()->getLocale() }}/location/', '');
                pathname = "/{{ app()->getLocale() }}/api/location/" + pathname;

                $.get(pathname,
                    {},
                    onAjaxSuccess2
                );

                function onAjaxSuccess2(data) {
                    loadpano('uzbekistan:' + data.id + '', 0, data.slug, '', '', 'nooo', data.video);
                }
            }
        });

        $('#feedbackForm').on('submit', function (e) {
            e.preventDefault();
            var ln = $('#mailll').val();
            var textarea = $('.feedbackPanel__wrapper-inputs textarea').val();

            if (ln.length > 0 && ln.indexOf("@") != -1 && textarea.length > 0) {
                $('.mail_inp').removeClass("red_color")
                $('.mail_inp2').removeClass("red_color")

                $('.send_form_btn').css('display', 'none')
                $('.block_thanks').css('display', 'flex')

                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                e.preventDefault();
                var message = $('textarea#feedbacktext').val();
                var select = $("#feedbackselect option:selected").text();
                ;
                var email = $("input[name=email]").val();
                $.ajax({
                    type: 'POST',
                    url: '/form/1',
                    data: {message: message, select: select, email: email},
                    success: function (data) {
                        // $("#Feedbackstatus").text('Спасибо за ваше сообщение!');
                    }
                });

                $('#sendinggg').click(function () {
                    $('.wrapper-panel-close').trigger('click');

                    setTimeout(function () {
                        $('#feedbackselect option:eq(0)').attr('selected', 'selected');
                        $('#mailll').val('');
                        $('.mail_inp2 textarea').val('');
                        $('.block_thanks').css('display', 'none')
                        $('.send_form_btn').css('display', 'block')
                    }, 500);
                });
            } else {
                $('.mail_inp').addClass("red_color")
                $('.mail_inp2').addClass("red_color")
            }
        });

        var krpano = null;
        var currentVideoVolume = '0';

        //код выполняется, когда панорама загружена (preload)
        function krpano_onready_callback(krpano_interface) {
            krpano = krpano_interface;
            setTimeout(function () {
                @if(isset($krhotspots[0]))
                var tmp = "{{$krhotspots[0]['location_id']}}";
                $.get('/{{ app()->getLocale() }}/api/hotspots/' + tmp).done(function (data) {

                    for (var i = 0; i < data.length; i++) {

                            add_exist_hotspot(data[i].h,
                                data[i].v,
                                data[i].name,
                                data[i].cat_icon_svg,
                                data[i].cat_icon,
                                data[i].img,
                                "uzbekistan:" + data[i].destination_id,
                                i,
                                data[i].slug,
                                data[i].color,
                                data[i].type,
                                data[i].information,
                                data[i].image,
                                data[i].video,
                                {url: data[i].url, file: data[i].file, instagram: data[i].instagram_hotspot,
                                    images: data[i].images, title: data[i].title, description: data[i].information_description,
                                    information_logo: data[i].information_logo, name: data[i].name, logo: data[i].logo}
                            );

                    }
                });
{{--                @else--}}
{{--                @foreach($krhotspots as $index => $hotspot)--}}
{{--                @if ($hotspot->type == App\Hotspot::TYPE_POLYGON)--}}
{{--                @php--}}
{{--                    continue;--}}
{{--                @endphp--}}
{{--                @endif--}}
{{--                @php--}}
{{--                    $informationText = str_replace("\r", "<br>", strip_tags($hotspot->information));--}}
{{--                    $informationText = str_replace('"', '\"', $informationText);--}}
{{--                    $informationText = str_replace("'", "\'", $informationText);--}}
{{--                    $informationText = str_replace(PHP_EOL, '\\' . PHP_EOL, $informationText);--}}
{{--                @endphp--}}
{{--                --}}{{--let info;--}}
{{--                --}}{{--info = $.isString(data[i].information) ? data[i].information : data[i].information["{{app()->getLocale()}}"];--}}
{{--                --}}{{--info = info !== '' ? info : data[i].information['ru'];--}}
{{--                @if($informationText !== '' && $hotspot->type == 2 || $hotspot->type !== 2)--}}

{{--                add_exist_hotspot(--}}
{{--                    "{{ $hotspot->h }}",--}}
{{--                    "{{ $hotspot->v }}",--}}
{{--                    "{!! str_replace('"', '\"', $hotspot->name) !!}",--}}
{{--                    "{{$hotspot->cat_icon_svg}}", "{{$hotspot->cat_icon}}",--}}
{{--                    "{{$hotspot->mainlocation->video ? ('/panoramas/preview/' . $hotspot->mainlocation->preview) : $hotspot->img}}",--}}
{{--                    "uzbekistan:{{ $hotspot->destination_id }}",--}}
{{--                        {{ $index }},--}}
{{--                    "{{$hotspot->slug}}",--}}
{{--                    "{{$hotspot->color}}",--}}
{{--                    "{{$hotspot->type}}",--}}
{{--                    { "{{app()->getLocale()}}": "{{$informationText}}"},--}}
{{--                    {"{{app()->getLocale()}}": "{{$hotspot->image}}", en: "{{$hotspot->image}}", ru: "{{$hotspot->image}}"},--}}
{{--                    "{{ $hotspot->destinationlocation->video }}",--}}
{{--                    {url: "{{$hotspot->url}}", file: "{{ $hotspot->file }}"}--}}
{{--                );--}}
{{--                @endif--}}
{{--                @endforeach--}}
                @endif
            }, 1000);
        }

        $(document).ready(function () {
            @if ($location->audio != '')
            $('#audio')[0].setSrc('/storage/audio/{{$location->audio}}');
            setTimeout(function () {
                // $('#audio')[0].play();
            }, 1000);
            @endif
        });

        @if(isset($hotspot))
        $(function () {
            $.get(
                // "/hasFloors/axmad4ik:{{ $hotspot->destination_id }}",
                // onAjaxSuccess
            );

            function onAjaxSuccess(data) {
                if (data == 1) {
                    $('.icon-ic_floorplan').parent().show();
                } else {
                    $('.icon-ic_floorplan').parent().hide();
                }
            }
        });

        @endif

        function krpanofullscreen() {
            krpano.call("set(fullscreen,true);");
            $('#logo2').css('display', 'block');
            $('#pano1').append(`<div id="logo2" onclick="krpanofullscreenexit()" class="icon-ic_windowed fullScreenIcon" style="display: block; position: absolute; z-index: 99999;"></div>`);
        }

        function krpanofullscreenexit() {
            krpano.call("set(fullscreen,false);");
            $('.icon-ic_fullscreen').removeClass('is-active')
            $('#logo2').remove();
        }

        function closerejimwindow() {
            var _this = $('.icon-ic_eye');
            var curModal = $('.' + _this.data('pannel')).eq(0);
            _this.removeClass('is-active');
            curModal.removeClass('visible').addClass('hidden');
        }

        function skin_view_fisheye() {
            closerejimwindow();
            this.setLookStraight(), this.krpano.set("view.architectural", !1), this.krpano.set("view.pannini", !1), this.krpano.set("view.stereographic", !1), this.krpano.call("tween(view.vlookat,       0, 0.5)"), this.krpano.call("tween(view.fisheye,       1.0, 1)"), this.krpano.call("tween(view.fovmax,       150, 1)"), this.krpano.call("tween(view.fov,       150, 0.5)")
        }

        function skin_view_panini() {
            closerejimwindow();
            this.setLookStraight(), this.krpano.set("view.architectural", !1), this.krpano.set("view.stereographic", !1), this.krpano.set("view.pannini", !0), this.krpano.call("tween(view.fovmax,       130, 0.5)"), this.krpano.call("tween(view.fov,       100, 0.5)"), this.krpano.call("tween(view.vlookat,       0, 0.5)"), 0.1 > this.krpano.get("view.fisheye") && this.krpano.call("tween(view.fisheye, 1.0, distance(1.0,0.8))")
        }

        function krpanoautorotate() {
            krpano.call("switch(autorotate.enabled)");
        }

        function skin_view_littleplanet() {
            closerejimwindow();
            this.krpano.set("view.architectural", !1), this.krpano.set("view.pannini", !1), this.krpano.set("view.stereographic", !0), this.krpano.call("tween(view.fisheye,       1.0, 0.5)"), this.krpano.call("tween(view.vlookat,       77, 0.5)"), this.krpano.call("tween(view.fovmax,       150, 0.5)"), this.krpano.call("tween(view.fov,       150, 0.5)")
        }

        function krpanoscreenshot() {
            krpano.call("makescreenshot();");
        }

        function setLookStraight() {
            closerejimwindow();
            var e = this.krpano.get("view.vlookat");
            (-80 >= e || 80 <= e) && (this.krpano.call("tween(view.vlookat, 0.0, 1.0, easeInOutSine)"), this.krpano.call("tween(view.fov, 150, distance(150,0.8))"))
        }

        function skin_view_stereographic() {
            closerejimwindow();
            this.setLookStraight(), this.krpano.set("view.architectural", !1), this.krpano.set("view.pannini", !1), this.krpano.set("view.stereographic", !0), this.krpano.call("tween(view.vlookat,       0, 0.5)"), this.krpano.call("tween(view.fovmax,       120, 0.5)"), this.krpano.call("tween(view.fov,       90, 0.5)"), this.krpano.call("tween(view.fisheye,       1.0, distance(1.0,0.8))")
        }

        function skin_view_architectural() {
            closerejimwindow();
            this.setLookStraight(), this.krpano.set("view.architectural", !0), this.krpano.set("view.pannini", !1), this.krpano.set("view.stereographic", !1), this.krpano.call("tween(view.vlookat,       0, 0.5)"), this.krpano.call("tween(view.fisheye, 0, distance(1.0,0.8))"), this.krpano.call("tween(view.fovmax,       120, 0.5)"), this.krpano.call("tween(view.fov,       70, 0.5)")
        }

        function skin_view_normal() {
            closerejimwindow();
            this.setLookStraight(), this.krpano.set("view.architectural", !1), this.krpano.set("view.pannini", !1), this.krpano.set("view.stereographic", !1), this.krpano.call("tween(view.fov,       90, 1"), this.krpano.call("tween(view.fovmax,       120, 1"), this.krpano.call("tween(view.vlookat,       0, 0.5)"), this.krpano.call("tween(view.fisheye,       0.0, 0.5)"), this.krpano.call("tween(view.pannini,       0.0, 0.5)")
        }

        function uzbsharefb() {
            var shareurl = $('#previewlinkurlshare').val();
            var sharequote = $('#location_name').text();
            window.open("https://www.facebook.com/sharer/sharer.php?u=" + shareurl + "&quote=" + sharequote, "myWindow", 'width=600,height=500');
            window.close();
        }

        function uzbsharetg() {
            var shareurl = $('#previewlinkurlshare').val();
            var sharequote = $('#location_name').text();
            window.open("https://telegram.me/share/url?url=" + shareurl + "&text=" + sharequote, "myWindow", 'width=600,height=500');
            window.close();
        }

        function uzbsharewt() {
            var shareurl = $('#previewlinkurlshare').val();
            var sharequote = $('#location_name').text();

            window.open("https://api.whatsapp.com/send?text=" + sharequote + " - " + shareurl, "myWindow", 'width=600,height=500');
            window.close();
        }

        function uzbshareVK() {
            var shareurl = $('#previewlinkurlshare').val();
            var sharequote = $('#location_name').text();
            window.open("https://vk.com/share.php?url=" + shareurl, "myWindow", 'width=600,height=500');
            window.close();
        }

        function uzbshareTwitter() {
            var shareurl = $('#previewlinkurlshare').val();
            var sharequote = $('#location_name').text();
            window.open("http://twitter.com/share?text=" + sharequote + "&url=" + shareurl + "&text=" + sharequote, "myWindow", 'width=600,height=500');
            window.close();
        }

        function remove_all_hotspots() {
            if (krpano) {
                krpano.call("loop(hotspot.count GT 0, removehotspot(0) );");
            }
        }

        function loadpano(xmlname, index, url, prevsceneid, prevsceneslug, nourl, video) {
            if (krpano) {
{{--                @php--}}
{{--                    $prevLocation = \App\Location::where('slug', prevsceneslug)->first();--}}
{{--                @endphp--}}
                originalxmlnam = xmlname;
                originalxmlname = originalxmlnam.match(/\d/g);
                originalxmlname = originalxmlname.join("");

                xmlname = xmlname.split(':')[1];
                var tmp = xmlname;
                xmlname = "/{{ app()->getLocale() }}/krpano/" + index + '/' + xmlname;
                remove_all_hotspots();
                if (video === null || video === '') {
                    krpano.call("removelayer('skin_layer', true)");
                    krpano.call("loadpano(" + xmlname + ", null, MERGE|KEEPBASE|KEEPHOTSPOTS, ZOOMBLEND(1,2,easeInQuad));");
                    krpano.call("loadscene('scene1', null, MERGE|KEEPBASE, ZOOMBLEND(1,2,easeInQuad));");
                } else {
                    let videoXml = '';
                    @php
                    if(config('app.env') === 'production') {
                        $protocolName = 'https://';
                    } else {
                        $protocolName = 'http://';
                    }
                    @endphp
                    let xmlVideo = $.get('{{$protocolName . request()->getHost()}}' + xmlname, function (response) {
                        krpano.call("loadxml(" + response + ")");
                        krpano.call("loadscene('scene2', null, MERGE, ZOOMBLEND(1,2))");
                    });
                }

                xmlname = xmlname.split('/').join(':');
                xmlname = xmlname.replace(':', '/');
                xmlname = xmlname.replace(':', '');

                $.get("/ru/hasFloors" + xmlname,
                    onAjaxSuccess
                );

                function onAjaxSuccess(data) {
                    if (data == 1) {
                        $('.icon-ic_floorplan').parent().show();
                    } else {
                        $('.icon-ic_floorplan').parent().hide();
                    }
                }

                //TODO: сделать сначала перевод камеры, а потом смену локации.
                // Также нужно задавать в админку точку, на которой сразу будет показываться панорама
                // krpano.call("movecamera(0,0);");
                if (nourl != "nooo") {
                    history.pushState({
                        id: 'homepage'
                    }, 'Home | My App', '/{{app()->getLocale()}}/location/' + url + '');
                }

                $.get('/{{app()->getLocale()}}/api/location/' + url).done(function (data) {
                    $("#location_name").text(data.name);
                    $("#location_name2 h1").text(data.name);
                    $('.infoPanel .infoPanel__current-categories .icon-wrapper__icon--category img').attr('src', '/storage/cat_icons/' + data.category_icon);
                    $.fancybox.close();

                    if (data.working_hours) {
                        $("#vremyarabotibox").show();
                        $("#vremyaraboti").text(data.working_hours);
                    } else {
                        $("#vremyarabotibox").hide()
                    }
                    if (data.number) {
                        $("#location_number_box").show();
                        $("#location_number").attr("href", "tel:" + data.number);
                        $("#location_number").text(data.number);
                    } else {
                        $("#location_number_box").hide();
                    }
                    if (data.description) {
                        $("#location_description").text(data.description);
                    } else {
                        $("#location_description").text("");
                    }
                    if (data.address) {
                        $("#location_adress_box").show();
                        $("#location_adress").text(data.address);
                    } else {
                        $("#location_adress_box").hide();
                    }
                    if (data.facebook) {
                        $(".socialnetwork-icon.facebook").show();
                        $("#locationsocialfb").attr("href", data.facebook);
                    } else {
                        $(".socialnetwork-icon.facebook").hide();
                    }
                    if (data.telegram) {
                        $(".socialnetwork-icon.telegram").show();
                        $("#locationsocialtg").attr("href", data.telegram);
                    } else {
                        $(".socialnetwork-icon.telegram").hide();
                    }
                    if (data.instagram) {
                        $(".socialnetwork-icon.instagram").show();
                        $("#locationsocialig").attr("href", data.instagram);
                    } else {
                        $(".socialnetwork-icon.instagram").hide();
                    }

                    if (data.website) {
                        $("#website_box").show();
                        $("#website_box a").html(data.website);
                        $("#website_box a").attr("href", data.website);
                    } else {
                        $("#website_box").hide();
                    }

                    if (data.etaji.length > 0) {
                        var flName = data.etaji[0].name;
                        flName = flName.{{ Lang::locale() }} === undefined ? flName.ru : flName.{{ Lang::locale() }};

                        $('.floorplan-viewer__header__name').html(data.name + ', ' + flName);
                        var iFloor;
                        var floorId;
                        var floorObject;
                        var code;
                        var allFloors = [];
                        $('#floor-krpano').html('');
                        $('.floorplan-viewer__footer').html('');

                        for (iFloor = 0; iFloor < data.etaji.length; ++iFloor) {
                            $('#floor-krpano').append(
                                '<div id="floorplan-tab' + iFloor + '" class="floorplan-tab" style="display: none;">\
                                <div class="plan">\
                                    <img class="planClass" id="floorid' + data.etaji[iFloor].id + '"  src="/storage/floors/' + data.etaji[iFloor].image + '">\
                                        <span ></span>\
                                </div>\
                            </div>'
                            );
                            var floorName = data.etaji[iFloor].name.{{ Lang::locale()  }}, localeFlo =  Object.keys(data.etaji[iFloor].name)[0];
                            if (floorName === undefined)
                                floorName = data.etaji[iFloor].name[localeFlo];
                            $('.floorplan-viewer__footer').append(
                                '<li class="floorplan-viewer__footer__element buttonetaj' + iFloor + '" data-tab="' + iFloor + '">\
                                <img class="floorplan-viewer__footer__element__icon" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4NCjxzdmcgd2lkdGg9IjM0cHgiIGhlaWdodD0iMzNweCIgdmlld0JveD0iMCAwIDM0IDMzIiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPg0KICAgIDwhLS0gR2VuZXJhdG9yOiBTa2V0Y2ggNDguMiAoNDczMjcpIC0gaHR0cDovL3d3dy5ib2hlbWlhbmNvZGluZy5jb20vc2tldGNoIC0tPg0KICAgIDx0aXRsZT5pY19sZXZlbDwvdGl0bGU+DQogICAgPGRlc2M+Q3JlYXRlZCB3aXRoIFNrZXRjaC48L2Rlc2M+DQogICAgPGRlZnM+PC9kZWZzPg0KICAgIDxnIGlkPSJIb21lX0Zsb29ycGxhbl92MiIgc3Ryb2tlPSJub25lIiBzdHJva2Utd2lkdGg9IjEiIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTMxMi4wMDAwMDAsIC0xMDA4LjAwMDAwMCkiPg0KICAgICAgICA8ZyBpZD0iRmxvb3JwbGFuIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSg4LjAwMDAwMCwgNjQuMDAwMDAwKSIgZmlsbD0iI0ZGRkZGRiI+DQogICAgICAgICAgICA8ZyBpZD0ibGV2ZWxzIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgyODguMDAwMDAwLCA5MzYuMDAwMDAwKSI+DQogICAgICAgICAgICAgICAgPGcgaWQ9ImxldmVsc19zZWxlY3QiPg0KICAgICAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMzMsMzguMTc0NSBDMjQuMDI3LDM4LjE3NDUgMTgsMzYuMTA2NSAxOCwzNC4xNzQ1IEMxOCwzMi40NzQ1IDIyLjY3NCwzMC42Njk1IDI5LjkxMiwzMC4yNjE1IEwzMywzMy4zNDk1IEwzNi4wODgsMzAuMjYxNSBDNDMuMzI2LDMwLjY2OTUgNDgsMzIuNDc0NSA0OCwzNC4xNzQ1IEM0OCwzNi4xMDY1IDQxLjk3MywzOC4xNzQ1IDMzLDM4LjE3NDUgTTMzLDEzLjk5OTUgQzM1LjQ4NSwxMy45OTk1IDM3LjUsMTYuMDE0NSAzNy41LDE4LjQ5OTUgQzM3LjUsMjAuOTg1NSAzNS40ODUsMjIuOTk5NSAzMywyMi45OTk1IEMzMC41MTUsMjIuOTk5NSAyOC41LDIwLjk4NTUgMjguNSwxOC40OTk1IEMyOC41LDE2LjAxNDUgMzAuNTE1LDEzLjk5OTUgMzMsMTMuOTk5NSBNMzcuOTU2LDI4LjM5MzUgTDQwLjQyNSwyNS45MjQ1IEM0NC41MiwyMS44MzA1IDQ0LjUyLDE1LjE2OTUgNDAuNDI1LDExLjA3NTUgQzM4LjQ0MSw5LjA5MjUgMzUuODA1LDcuOTk5NSAzMyw3Ljk5OTUgQzMwLjE5NSw3Ljk5OTUgMjcuNTU5LDkuMDkyNSAyNS41NzQsMTEuMDc1NSBDMjEuNDgsMTUuMTY5NSAyMS40OCwyMS44MzA1IDI1LjU3NSwyNS45MjQ1IEwyOC4wNDQsMjguMzkzNSBDMjEuNzU0LDI4Ljk1NDUgMTYsMzAuNjY0NSAxNiwzNC4xNzQ1IEMxNiwzOC42MDM1IDI1LjE1OCw0MC4xNzQ1IDMzLDQwLjE3NDUgQzQwLjg0Miw0MC4xNzQ1IDUwLDM4LjYwMzUgNTAsMzQuMTc0NSBDNTAsMzAuNjY0NSA0NC4yNDYsMjguOTU0NSAzNy45NTYsMjguMzkzNSIgaWQ9ImljX2xldmVsIj48L3BhdGg+DQogICAgICAgICAgICAgICAgPC9nPg0KICAgICAgICAgICAgPC9nPg0KICAgICAgICA8L2c+DQogICAgPC9nPg0KPC9zdmc+">\
                                <span class="floorplan-viewer__footer__element__name">' + data.name + ', ' + floorName+ '</span>\
                            </li>'
                            );
                            let initHeight = data.etaji[iFloor].height;
                            let frameHeight = initHeight > 1029 ? $(window).height() - 300 : initHeight;
                            let frameWidth = 'auto';
                            if (isMobile) {
                                frameHeight = ($(window).height() - 300) + 'px';
                            } else {
                                frameWidth = data.etaji[iFloor].width + 'px';
                                frameHeight = frameHeight + 'px';
                            }

                            floorObject = {
                                maxZoom: 1,
                                navigator: false,
                                navigatorImagePreview: false,
                                frameWidth: myWidth,
                                frameHeight: myHeight,
                                fullscreen: true,
                                iconsize: "15px",
                                // rubberband: true
                            };
                            code = data.etaji[iFloor].code;
                            code = eval('{' + code + '}');
                            floorObject.annotations = code;
                            allFloors[iFloor] = floorObject;
                        }

                        //добавление других локаций в мультиэтажности
                        $('#multifloor-other-locations').html('');
                        for (floorLocation = 0; floorLocation < data.floors_locations.length; ++floorLocation) {
                            let floorLocationItem = data.floors_locations[floorLocation];

                            $('#multifloor-other-locations').append('<div class="listItem-wrapper" style="height: 260px;"\
                                 onclick="loadpano(\'uzbekistan:' + floorLocationItem.id + '\', ' + floorLocation + ', \'' + floorLocationItem.slug + '\', \'\', \'\', \'\', '+ (floorLocationItem.video ? '\'' + floorLocationItem.video + '\'' : 'null') + ')">\
                                <div class="listItem" style="width: 224px; height: 244px;">\
                                    <div class="listItem__img"><img\
                                                src="/storage/panoramas/unpacked/' + floorLocationItem.img + '/thumb.jpg"\
                                                class="listItem__img--scene"></div>\
                                    <div class="listItem__icon-category">\
                                        <div class="icon-wrapper__icon--category category-normal"\
                                             style="background-color:color">\
                                            <img src="/storage/cat_icons/' + floorLocationItem.categorylocation.cat_icon_svg + '">\
                                        </div>\
                                    </div>\
                                    <div class="listItem__text">\
                                    {{-- TODO: сделать определение и подставку языка --}}\
                                        <div><span>' + floorLocationItem.name.ru + '</span></div>\
                                    </div>\
                                </div>\
                            </div>');
                        }

                        //показ первого этажа
                        floorId = data.etaji[0].id;
                        $("#floorid" + floorId).annotatorPro(
                            allFloors[0]
                        );

                        $('.floorplan-tab').eq(0).fadeIn();
                        $('.floorplan-viewer__footer li').eq(0).addClass(
                            'floorplan-viewer__footer__element--selected is-activated--categories');

                        //обработка нажатий кнопок выбора этажей
                        $('.floorplan-viewer__footer li').off('click');

                        $('.floorplan-viewer__footer li').on('click', function () {
                            var _this = $(this);
                            var curTab = $('#floorplan-tab' + _this.data('tab')).eq(0);

                            $('.floorplan-viewer__footer li').removeClass(
                                'floorplan-viewer__footer__element--selected is-activated--categories');
                            _this.addClass('floorplan-viewer__footer__element--selected is-activated--categories');

                            $('.floorplan-tab').fadeOut();

                            var annotatorImage = null;
                            setTimeout(function () {
                                annotatorImage = $(".floorplan-tab").eq(_this.index()).find('img.planClass');
                                annotatorImage.annotatorPro(
                                    allFloors[_this.data('tab')]
                                );
                            }, 500);

                            $('.floorplan-viewer__header__name').html($(this).find('.floorplan-viewer__footer__element__name').html());

                            setTimeout(function () {
                                curTab.fadeIn();
                            }, 400);
                        });
                        $('.buttonetaj0').show();
                    } else {
                        $('.buttonetaj0').hide();
                        $('.floorplan-viewer__header__name').val('');
                    }

                    $('#playaudio').find('img').attr('src', '/assets/icons/sound-off.svg');

                    if (data.audio) {
                        $('#audio')[0].setSrc('/storage/audio/' + data.audio);
                        $('#playaudio').show();
                        $('#playaudio').find('img').attr('src', '/assets/icons/sound-off.svg');
                    } else {
                        if (data.podlocparent_id) {
                            $.get('/{{app()->getLocale()}}/api/location/' + data.podlocparent_id)
                                .done(function (parentLocData) {
                                    if (parentLocData.audio) {
                                        $('#playaudio').show();
                                        $('#playaudio').find('img').attr('src', '/assets/icons/sound-off.svg');
                                    }
                                });
                        } else {
                            $('#playaudio').hide();
                            $('#audio')[0].pause();
                        }
                    }
                    if (typeof data.videos != 'undefined' && data.videos.length > 0) {
                        $('#playaudio').show();
                        $('#playaudio').off('click');
                        $('#playaudio').on('click', function () {
                            $.each(data.videos, function (key) {
                                if (krpano.get('hotspot[video' + key + '].volume') == 0) {
                                    krpano.set('hotspot[video' + key + '].volume', '1.0');
                                    $('#playaudio').find('img').attr('src', '/assets/icons/sound-on.svg');
                                    currentVideoVolume = '1.0';
                                } else {
                                    krpano.set('hotspot[video' + key + '].volume', '0');
                                    $('#playaudio').find('img').attr('src', '/assets/icons/sound-off.svg');
                                    currentVideoVolume = '0';
                                }
                            });
                        });

                        //принудительное включение звука для видео
                        $.each(data.videos, function (key) {
                            if (currentVideoVolume !== '0' && currentVideoVolume !== null) {
                                $('#playaudio').find('img').attr('src', '/assets/icons/sound-on.svg');
                                setTimeout(function () {
                                    krpano.set('hotspot[video' + key + '].volume', currentVideoVolume);
                                }, 500);

                            } else {
                                $('#playaudio').find('img').attr('src', '/assets/icons/sound-off.svg');
                            }
                        });
                    }
                    if (data.onmap == "on") {
                        $('.currentlocationcordinates').data('lat', data.lat);
                        $('.currentlocationcordinates').data('lng', data.lng);
                    } else {
                        $('.currentlocationcordinates').data('map', 'no');
                        $('.currentlocationcordinates').data('lat', 'no');
                        $('.currentlocationcordinates').data('lng', 'no');
                    }
                    let videoVal = null;
                    if (data.is_sky == "on") {
                        // if (video) {
                        //     videoVal = "'" + video + "'";
                        // }
                        document.getElementById("hubviewlink").setAttribute("onclick", "loadpano('uzbekistan:" + prevsceneid + "', '0', '" + prevsceneslug + "', '" + originalxmlname + "','" + url + "', 'nooo', " + videoVal + ");");
                        document.getElementById("hubviewlink2").setAttribute("onclick", "loadpano('uzbekistan:" + prevsceneid + "', '0', '" + prevsceneslug + "', '" + originalxmlname + "','" + url + "', 'nooo', " + videoVal + ");");
                    } else {
                        if (data.sky_video) {
                            videoVal = "'" + data.sky_video + "'";
                        }
                        document.getElementById("hubviewlink").setAttribute("onclick", "loadpano('uzbekistan:" + data.sky_id + "', '0', '" + data.skyslug + "', '" + originalxmlname + "','" + url + "', 'nooo',  " + videoVal + ");");
                        document.getElementById("hubviewlink2").setAttribute("onclick", "loadpano('uzbekistan:" + data.sky_id + "', '0', '" + data.skyslug + "', '" + originalxmlname + "','" + url + "', 'nooo', " + videoVal + ");");
                    }
                });
                if ($( ".wrapper-panel.floorplanPanel" ).hasClass( "visible" )) {
                    $('.icon-ic_close.close').click()
                }
                $.get('/{{ app()->getLocale() }}/api/hotspots/' + tmp).done(function (data) {

                    for (var i = 0; i < data.length; i++) {

                            add_exist_hotspot(data[i].h,
                                data[i].v,
                                data[i].name,
                                data[i].cat_icon_svg,
                                data[i].cat_icon,
                                data[i].img,
                                "uzbekistan:" + data[i].destination_id,
                                i,
                                data[i].slug,
                                data[i].color,
                                data[i].type,
                                data[i].information,
                                data[i].image,
                                data[i].video,
                                {url: data[i].url, file: data[i].file, instagram: data[i].instagram_hotspot,
                                    images: data[i].images, title: data[i].title, description: data[i].information_description,
                                    information_logo: data[i].information_logo, name: data[i].name, logo: data[i].logo}
                            );

                    }
                });

            }
        }

        function add_exist_hotspot(h, v, name, cat_icon_svg, cat_icon, img, hs_name, index, slug, color, type, information, image, video, informationOptions) {
            hs_name = hs_name + ':' + index;
            type = type == null ? 1 : type;
            if (krpano) {
                krpano.call("addhotspot(" + hs_name + ")");
                krpano.set("hotspot[" + hs_name + "].keep", "true");
                if (type == {{ \App\Hotspot::TYPE_INFORMATION }}) {
                    krpano.set("hotspot[" + hs_name + "].url", "/assets/icons/information-icon.png");
                } else if (type == {{ \App\Hotspot::TYPE_POLYGON }}) {
                    
                } else {
                    krpano.set("hotspot[" + hs_name + "].url", "/storage/cat_icons/" + cat_icon + "");
                }

                krpano.set("hotspot[" + hs_name + "].ath", h);
                krpano.set("hotspot[" + hs_name + "].atv", v);
                r = h;
                krpano.set("hotspot[" + hs_name + "].scale", hotspotsize);
                krpano.set("hotspot[" + hs_name + "].edge", "bottom");

                krpano.set("hotspot[" + hs_name + "].onout", function () {
                    $(".hotspotPreview-wrapper").hide();
                });

                krpano.set("hotspot[" + hs_name + "].type", type);
                krpano.set("hotspot[" + hs_name + "].zorder", '99');
                krpano.set("hotspot[" + hs_name + "].onover", function () {
                    if (type == {{ \App\Hotspot::TYPE_INFORMATION }})
                        return false;
                    $(".hotspotPreview-wrapper").show();
                    hotspottext = $('.hotspotPreview__text');
                    hotspoticon = $('.uzbhotspoticon');
                    hotspotimg = $('.uzbhotspotimg');
                    var n = krpano.spheretoscreen(h, v);
                    var m = krpano.get("state.position.location");

                    var link = $('.hotspotPreview__text');

                    var uzb360preview = $('#uzb360preview');
                    var bottomFromVisota = $(document).height();
                    var bottomFromShirota = $(document).width();

                    var preview = $('.hotspotPreview ');
                    var previewx = n.x + 50;
                    var previewxx = n.x - 280;
                    var previewxxx = n.x - 120;
                    var previewy = n.y + 30;
                    var previewyy = n.y - 200;
                    var top = n.y - 325;
                    var bottom = n.y + 30;
                    var left = n.x - 118;
                    var xxx = bottomFromShirota - (bottomFromShirota - n.x);
                    var xxxx = bottomFromVisota - (bottomFromVisota - n.y);
                    if (bottomFromShirota - n.x > 500) {
                        preview.css('left', '' + previewx + 'px')
                        preview.css('top', '' + previewyy + 'px')

                        uzb360preview.removeClass();
                        uzb360preview.addClass('hotspotPreview right');
                    } else {
                        preview.css('left', '' + previewxx + 'px')
                        preview.css('top', '' + previewyy + 'px')
                        uzb360preview.removeClass();
                        uzb360preview.addClass('hotspotPreview left');
                    }

                    if (bottomFromVisota - n.y < 90 && bottomFromShirota - n.x > 150 && xxx > 150) {
                        preview.css('left', '' + left + 'px')
                        preview.css('top', '' + top + 'px')
                        uzb360preview.removeClass();
                        uzb360preview.addClass('hotspotPreview bottom');
                    }

                    if (xxxx < 254 && bottomFromShirota - n.x > 150 && xxx > 150) {
                        preview.css('left', '' + left + 'px')
                        preview.css('top', '' + bottom + 'px')
                        uzb360preview.removeClass();
                        uzb360preview.addClass('hotspotPreview top');
                    }

                    hotspottext.text(name);
                    hotspoticon.attr("src", "/storage/cat_icons/" + cat_icon_svg + "");
                    hotspoticon.css("background-color", color)
                    if (!video) {
                        hotspotimg.attr("src", "/storage/panoramas/unpacked/" + img + "/thumb.jpg");
                    } else {
                        hotspotimg.attr("src", img);
                    }

                });

                krpano.set("hotspot[" + hs_name + "].distorted", false);

                if (krpano.get("device.html5")) {
                    krpano.set("hotspot[" + hs_name + "].onclick", function (hs) {
                        var showModal = true;
                        if (type == {{ \App\Hotspot::TYPE_INFORMATION }}) {
                            var getLocale = "{{app()->getLocale()}}";

                            $('.information-buttons').html('');

                            information = jQuery.type(information) === "string" ?  information.replaceAll('\\"', '"') : information[getLocale]
                            image = jQuery.type(image) === "string" ?  image : image[getLocale]
                            $('#information-modal .content').html(information);
                            if (image) {
                                $('.image-block').html('<img/>');
                                $('.image-block img').attr('src', '/storage/information/' + image);
                            } else {
                                $('.image-block').html('');
                            }
                            if (informationOptions.hasOwnProperty('url')) {
                                if (informationOptions.url != '' && informationOptions.url != null) {
                                    $('.information-buttons').append('<a class="btn url-button" target="_blank"><img src="{{asset('assets/icons/url.png')}}"/><br>{{ trans('uzb360.website')}}</a>');
                                    $('.information-buttons a.url-button').attr('href', informationOptions.url);
                                }
                            }
                            if (informationOptions.hasOwnProperty('instagram')) {
                                if (informationOptions.instagram != '' && informationOptions.instagram != null) {
                                    $('.information-buttons').append('<a class="btn instagram-button" target="_blank"><img src="{{asset('assets/icons/instagram.png')}}"/><br>Instagram</a>');
                                    $('.information-buttons a.instagram-button').attr('href', informationOptions.instagram);
                                }
                            }
                            if (informationOptions.file) {
                                $('.information-buttons').append('<a class="btn texture-button"><img src="{{asset('assets/icons/3d-model.png')}}"/><br>{{ trans('uzb360.download_3d_model')}}</a>');
                                $('.information-buttons a.texture-button').attr('href', '/storage/information/' + informationOptions.file);
                            } else {
                                $('.information-buttons').html('');
                            }
                            if (informationOptions.hasOwnProperty('title')) {
                                if (informationOptions.title != '' && informationOptions.title != null) {
                                    $('.heading .title').html('<h5>' + informationOptions.title + '</h5>');
                                } else {
                                    $('.heading .title').html('');
                                }
                            } else {
                                $('.heading .title').html('');
                            }
                            if (informationOptions.hasOwnProperty('description')) {
                                if (informationOptions.description != '' && informationOptions.description != null) {
                                    $('.heading .description').html(informationOptions.description);
                                } else {
                                    $('.heading .description').html('');
                                }
                            } else {
                                $('.heading .description').html('');
                            }
                            $('.heading .information_logo').html('');
                            if (informationOptions.information_logo) {
                                $('.heading .logo').html('<img src="/storage/information/' + informationOptions.information_logo + '"/>');
                            } else {
                                $('.heading .logo').html('');
                            }

                            if (!informationOptions.title && !informationOptions.description && !informationOptions.information_logo) {
                                console.log('information:', information, 'logo:', informationOptions);
                                $('.heading').hide();
                                $('.centered-logo').html('<img src="/storage/locations/' + informationOptions.logo + '"/>');
                                $('.centered-logo').show();
                            } else {
                                $('.heading').show();
                                $('.centered-logo').hide();
                            }

                            $('#information-modal .images').html('');
                            if (informationOptions.images) {
                                $.each(informationOptions.images, function (key, value) {
                                    $('#information-modal .images').append('<a href="#"><img src="/storage/information/' + value + '"/></a>');
                                });
                            } else {
                                $('#information-modal .images').html('');
                            }

                            if (showModal) {
                                $.fancybox.open(
                                    $('#information-modal'),
                                    {
                                        // type: 'html',
                                        touch: false
                                    }
                                );
                            }
                        } else {
                            krpano.call("moveto("+h+","+v+",linear(45))");
                            setTimeout(function() {
                                loadpano(hs_name, index, slug, null, null, null, video);
                            }, 2000);
                        }

                    }.bind(null, hs_name));
                }
            }
        }

        function openModal(frame, text, link) {
            var lightbox = lity('/{{ app()->getLocale() }}/ajax-modal?frame=' + frame + '&text=' + text + '&link=' + link);
        }

        function initMap() {
            var location = new google.maps.LatLng({{$curlocation->lat}},{{$curlocation->lng}});
            var map = new google.maps.Map(document.getElementById('map'), {
                center: location,
                streetViewControl: false,
                mapTypeControlOptions: {
                    mapTypeIds: []
                }, // here´s the array of controls
                disableDefaultUI: true, // a way to quickly hide all controls
                mapTypeControl: true,
                scaleControl: true,
                gestureHandling: 'greedy',
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.LARGE
                },
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                clickableIcons: false,
                mapTypeControl: false
            });
            map.setZoom(14);
            map.setOptions({minZoom: 8});
            var locations = <?php print_r(json_encode($locationscordinate)) ?>;

            function project(latLng) {
                var TILE_SIZE = 256

                var siny = Math.sin(latLng.lat() * Math.PI / 180)
                siny = Math.min(Math.max(siny, -0.9999), 0.9999)

                return new google.maps.Point(
                    TILE_SIZE * (0.5 + latLng.lng() / 360),
                    TILE_SIZE * (0.5 - Math.log((1 + siny) / (1 - siny)) / (4 * Math.PI)))
            }

            function getMapDimenInPixels(map) {
                var zoom = map.getZoom()
                var bounds = map.getBounds()
                var southWestPixel = getPixel(bounds.getSouthWest(), zoom)
                var northEastPixel = getPixel(bounds.getNorthEast(), zoom)
                return {
                    width: Math.abs(southWestPixel.x - northEastPixel.x),
                    height: Math.abs(southWestPixel.y - northEastPixel.y)
                }
            }

            function getPixel(latLng, zoom) {
                var scale = 1 << zoom
                var worldCoordinate = project(latLng)
                return new google.maps.Point(
                    Math.floor(worldCoordinate.x * scale),
                    Math.floor(worldCoordinate.y * scale))
            }

            function willAnimatePanTo(map, destLatLng, optionalZoomLevel) {
                var dimen = getMapDimenInPixels(map)

                var mapCenter = map.getCenter()
                optionalZoomLevel = !!optionalZoomLevel ? optionalZoomLevel : map.getZoom()

                var destPixel = getPixel(destLatLng, optionalZoomLevel)
                var mapPixel = getPixel(mapCenter, optionalZoomLevel)
                var diffX = Math.abs(destPixel.x - mapPixel.x)
                var diffY = Math.abs(destPixel.y - mapPixel.y)

                return diffX < dimen.width && diffY < dimen.height
            }

            function getOptimalZoomOut(latLng, currentZoom) {
                if (willAnimatePanTo(map, latLng, currentZoom - 1)) {
                    return currentZoom - 1
                } else if (willAnimatePanTo(map, latLng, currentZoom - 2)) {
                    return currentZoom - 2
                } else {
                    return currentZoom - 3
                }
            }

            function smoothlyAnimatePanToWorkarround(map, destLatLng, optionalAnimationEndCallback) {
                var initialZoom = map.getZoom(), listener

                function zoomIn() {
                    if (map.getZoom() < initialZoom) {
                        map.setZoom(Math.min(map.getZoom() + 3, initialZoom))
                    } else {
                        google.maps.event.removeListener(listener)

                        map.setOptions({
                            draggable: true,
                            zoomControl: true,
                            scrollwheel: true,
                            disableDoubleClickZoom: false,
                            minZoom: 8
                        })

                        if (!!optionalAnimationEndCallback) {
                            optionalAnimationEndCallback()
                        }
                    }
                }

                function zoomOut() {
                    if (willAnimatePanTo(map, destLatLng)) {
                        google.maps.event.removeListener(listener)
                        listener = google.maps.event.addListener(map, 'idle', zoomIn)
                        map.panTo(destLatLng)
                    } else {
                        map.setZoom(getOptimalZoomOut(destLatLng, map.getZoom()))
                    }
                }

                map.setOptions({
                    draggable: false,
                    zoomControl: false,
                    scrollwheel: false,
                    disableDoubleClickZoom: true,
                    minZoom: 8
                })
                map.setZoom(getOptimalZoomOut(destLatLng, initialZoom))
                listener = google.maps.event.addListener(map, 'idle', zoomOut)
            }

            function smoothlyAnimatePanTo(map, destLatLng) {
                if (willAnimatePanTo(map, destLatLng)) {
                    map.panTo(destLatLng)
                } else {
                    smoothlyAnimatePanToWorkarround(map, destLatLng)
                }
            }

            $('.slick-block3').on('afterChange', function (event, slick, currentSlide, nextSlide) {
                if (window.innerWidth < 1025) {
                    var realslide = $(".slick-active", this);
                    var slidbox = $(".featuredloctionbox", realslide);
                    var latmark = slidbox.data('lat');
                    var lngmark = slidbox.data('lng');
                    smoothlyAnimatePanTo(map, new google.maps.LatLng(latmark, lngmark));
                }
            });
            $('.slick-block2').on('afterChange', function (event, slick, currentSlide, nextSlide) {
                if (window.innerWidth < 1025) {
                    $('.slick-block2 .slick-slide').removeClass('slick-current');
                    $('.slick-block2 .slick-slide:not(.slick-cloned)').eq(currentSlide).addClass('slick-current');
                    var realslide = $(".slick-active", this);
                    var slidbox = $(".featuredloctionbox", realslide);
                    var latmark = slidbox.data('lat');
                    var lngmark = slidbox.data('lng');
                    smoothlyAnimatePanTo(map, new google.maps.LatLng(latmark, lngmark));
                }
            });
            $('.featuredloctionbox').mouseover(function () {
                var latmark = $(this).data('lat');
                var lngmark = $(this).data('lng');
                smoothlyAnimatePanTo(map, new google.maps.LatLng(latmark, lngmark));
            });

            $('.icon-ic_explore').click(function () {
                var currentlocationcordinates = $(".currentlocationcordinates");
                var curmapchecl = currentlocationcordinates.data('map');
                var curlatmark = currentlocationcordinates.data('lat');
                var curlngmark = currentlocationcordinates.data('lng');
                if (curmapchecl !== "no" && curlatmark != "0" && curlngmark != "0") {

                    var curlatmark = currentlocationcordinates.data('lat');
                    var curlngmark = currentlocationcordinates.data('lng');
                    smoothlyAnimatePanTo(map, new google.maps.LatLng(curlatmark, curlngmark));
                }
            });

            $.each(locations, function (index, value) {
                var icon = {
                    url: '/storage/cat_icons/' + value.categorylocation.cat_icon, // url
                    scaledSize: new google.maps.Size(45, 55) // scaled size
                };

                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(value.lat, value.lng),
                    map: map,
                    animation: google.maps.Animation.DROP,
                    icon: icon,
                    maphotspotname: value.name,
                    maphotspoticon: value.categorylocation.cat_icon_svg,
                    maphotspotimg: value.img,
                    maphotspotcolor: value.categorylocation.color
                });

                function getPixelLocation(currentLatLng) {
                    var scale = Math.pow(2, map.getZoom());
                    var nw = new google.maps.LatLng(
                        map.getBounds().getNorthEast().lat(),
                        map.getBounds().getSouthWest().lng()
                    );
                    var worldCoordinateNW = map.getProjection().fromLatLngToPoint(nw);
                    var worldCoordinate = map.getProjection().fromLatLngToPoint(currentLatLng);
                    var currentLocation = new google.maps.Point(
                        Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale),
                        Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale)
                    );
                    return currentLocation;
                }

                marker.addListener('mouseout', function () {
                    $(".hotspotPreview-wrapper").hide();
                })
                marker.addListener('mouseover', function () {
                    var maphotspotname = this.maphotspotname;
                    var maphotspoticon = this.maphotspoticon;
                    var maphotspotimg = this.maphotspotimg;
                    var maphotspotcolor = this.maphotspotcolor;
                    $(".hotspotPreview-wrapper").show();
                    hotspottext = $('.hotspotPreview__text');
                    hotspoticon = $('.uzbhotspoticon');
                    hotspotimg = $('.uzbhotspotimg');
                    var projection = map.getProjection();
                    var markerLocation = marker.getPosition();
                    var latmark = $(this).data('lat');
                    var lngmark = $(this).data('lng');
                    var n = getPixelLocation(marker.getPosition());
                    var link = $('.hotspotPreview__text');
                    var uzb360preview = $('#uzb360preview');
                    var bottomFromVisota = $(document).height();
                    var bottomFromShirota = $(document).width();
                    var preview = $('.hotspotPreview ');
                    var previewx = n.x + 50;
                    var previewxx = n.x - 280;
                    var previewxxx = n.x - 120;
                    var previewy = n.y + 30;
                    var previewyy = n.y - 200;
                    var top = n.y - 325;
                    var bottom = n.y + 30;
                    var left = n.x - 118;
                    var xxx = bottomFromShirota - (bottomFromShirota - n.x);
                    var xxxx = bottomFromVisota - (bottomFromVisota - n.y);
                    if (bottomFromShirota - n.x > 500) {
                        preview.css('left', '' + previewx + 'px')
                        preview.css('top', '' + previewyy + 'px')
                        uzb360preview.removeClass();
                        uzb360preview.addClass('hotspotPreview right');
                    } else {
                        preview.css('left', '' + previewxx + 'px')
                        preview.css('top', '' + previewyy + 'px')
                        uzb360preview.removeClass();
                        uzb360preview.addClass('hotspotPreview left');
                    }
                    if (bottomFromVisota - n.y < 90 && bottomFromShirota - n.x > 150 && xxx > 150) {
                        preview.css('left', '' + left + 'px')
                        preview.css('top', '' + top + 'px')
                        uzb360preview.removeClass();
                        uzb360preview.addClass('hotspotPreview bottom');
                    }

                    if (xxxx < 254 && bottomFromShirota - n.x > 150 && xxx > 150) {
                        preview.css('left', '' + left + 'px')
                        preview.css('top', '' + bottom + 'px')
                        uzb360preview.removeClass();
                        uzb360preview.addClass('hotspotPreview top');
                    }

                    hotspottext.text(maphotspotname);
                    hotspoticon.attr("src", "/storage/cat_icons/" + maphotspoticon + "");
                    hotspoticon.css("background-color", maphotspotcolor)
                    hotspotimg.attr("src", "/storage/panoramas/unpacked/" + maphotspotimg + "/thumb.jpg");
                });

                google.maps.event.addDomListener(marker, 'click', function () {
                    loadpano('uzbekistan:' + value.id, '1', value.slug, '', '', 'nooo', value.video);
                    setTimeout(function () {
                        $('.icon-ic_close').trigger('click')
                    }, 100);
                });
            });
        }

        @if($location->panorama)
            embedpano({
                target: "pano",
                id: "pano1",
                xml: "/{{ app()->getLocale() }}/krpano/0/{{ $location->id }}",
                passQueryParameters: true,
                html5: "only+webgl",
                onready: krpano_onready_callback
            });
        @elseif($location->video)
            embedpano({
                target: "pano",
                id: "pano1",
                xml: "/{{ app()->getLocale() }}/krpano/video/{{ $location->id }}",
                passQueryParameters: true,
                html5: "only+webgl",
                onready: krpano_onready_callback
            });
        @endif
    </script>
@endsection
