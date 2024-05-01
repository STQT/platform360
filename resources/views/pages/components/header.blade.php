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
            <div class="dubai360-tooltip">
                <span>{{ $allTranslations['hubrejim'] ?? trans('uzb360.hubrejim')}}</span></div>
        </div>
        <div class="wrapper-button">
            <span class="icon-ic_explore wrapper-button__icon " data-pannel="explorePannel"></span>
            <div class="dubai360-tooltip">
                <span>{{$allTranslations['map'] ?? trans('uzb360.map')}}</span></div>
        </div>
        <div class="wrapper-button">
            <span class="icon-ic_glass wrapper-button__icon " data-pannel="search"></span>
            <div class="dubai360-tooltip">
                <span>{{$allTranslations['search'] ?? trans('uzb360.search')}}</span></div>
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
            <div class="dubai360-tooltip">
                <span>{{ $allTranslations['information'] ?? trans('uzb360.information')}}</span>
            </div>
        </div>
        <div class="wrapper-button" onclick="krpanoscreenshot();">
            <span class="icon-ic_share wrapper-button__icon " data-pannel="sharePannel"></span>
            <div class="dubai360-tooltip">
                <span>{{ $allTranslations['share'] ?? trans('uzb360.share')}}</span></div>
        </div>
        <div class="wrapper-button" id="autotourbutton" onclick="krpanoautorotate();">
            <span class="icon-ic_autoplay wrapper-button__icon "></span>
            <div class="dubai360-tooltip">
                <span>{{ $allTranslations['tourrejim'] ?? trans('uzb360.tourrejim')}}</span></div>
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
            <div class="dubai360-tooltip">
                <span>{{ $allTranslations['fullscreen'] ?? trans('uzb360.fullscreen')}}</span></div>
        </div>

        <div class="wrapper-button">
            <span class="icon-ic_eye wrapper-button__icon " data-pannel="ProjectionsPannel"></span>
            <div class="dubai360-tooltip">
                <span>{{ $allTranslations['rejimprosmotra'] ?? trans('uzb360.rejimprosmotra')}}</span>
            </div>
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
              style="fill:#1a90d2;fill-opacity:1"/>
      <circle
              class="cls-1"
              cx="30.469999"
              cy="31.530001"
              r="11.24"
              id="circle911"
              style="fill:#1a90d2;fill-opacity:1"/>
    </g>
  </g>
</svg>


    </span>
        <div class="language-switcher">
            <div class="dropdown-wrapper">
                <select name="select" class="dropdown">
                    <option value="ru" id="ru" @if(Lang::locale()=='ru') selected @endif>RU</option>
                    <option value="en" id="en" @if(Lang::locale()=='en') selected @endif>EN</option>
                    <option value="es" id="es" @if(Lang::locale()=='es') selected @endif>ES</option>
                    <option value="tr" id="tr" @if(Lang::locale()=='tr') selected @endif>TR</option>
                </select>
                <img src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jaGV2cm9uPC90aXRsZT48cG9seWdvbiBjbGFzcz0iY2xzLTEiIHBvaW50cz0iMTIgMTQgOSAxMSAxNSAxMSAxMiAxNCIvPjwvc3ZnPg=="
                     class="dropdown-chevronImg">
            </div>
        </div>
    </div>
</header>