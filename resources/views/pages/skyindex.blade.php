@extends('layouts.krpano')

@section('content')
<meta name="csrf-token" content="{!! csrf_token() !!}">
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

                <!-- <div id="logo2" class="icon-ic_windowed fullScreenIcon" style="display: block;"></div> -->
                <header class="dubai360-header">
                    <div class="dubai360-header__logo-languaje"><img src="/assets/360.svg" class="" width="100%"></div>
                    <div class="dubai360-header__icons">
                        <div class="wrapper-button">
                            <span class="icon-ic_aerial wrapper-button__icon "></span>
                            <div class="dubai360-tooltip"><span >Режим Хаб</span></div>
                        </div>
                        <div class="wrapper-button">
                            <span class="icon-ic_explore wrapper-button__icon " data-pannel="explorePannel"></span>
                            <div class="dubai360-tooltip"><span>Карта</span></div>
                        </div>
                        <div class="wrapper-button">
                            <span class="icon-ic_glass wrapper-button__icon " data-pannel="search"></span>
                            <div class="dubai360-tooltip"><span>Поиск</span></div>
                        </div>
                        <div class="wrapper-button">
                            <span class="icon-ic_comment wrapper-button__icon " data-pannel="feedbackPannel"></span>
                            <div class="dubai360-tooltip"><span>Обратная связь</span></div>
                        </div>
                        <div class="wrapper-button">
                            <span class="icon-ic_question wrapper-button__icon " data-pannel="helpPannel"></span>
                            <div class="dubai360-tooltip"><span>Помощь</span></div>
                        </div>
                    </div>
                    <div class="dubai360-header__aloneicon">
                        <div class="wrapper-button active">
                            <span class="icon-ic_floorplan wrapper-button__icon" data-pannel="floorplanPanel"></span>
                            <div class="dubai360-tooltip">
                                <span>Этажи</span>
                            </div>
                        </div>
                    </div>
                    <div class="dubai360-header__description">
                        <div><span><span><span id="location_name">{{ $location->name }}</span></span><span style="position: fixed; visibility: hidden; top: 0px; left: 0px;">…</span></span></div>


                    </div>

                    <div class="dubai360-header__icons">
                        <!-- *********** TImberland Block *********** -->
                        <div class="input_city">
                           <div class="drop_down_city">


                             <select>
                                 @foreach($cities as $city)
                                 <option id="{{$city->id}}" @if($defaultlocation==$city->id) selected @endif>{{$city->name}}</option>

                                 @endforeach
                             </select>
                           </div>


                        </div>
                        <!-- **************************************** -->
                        <div class="wrapper-button">
                            <span class="icon-ic_info wrapper-button__icon " data-pannel="infoPannel"></span>
                            <div class="dubai360-tooltip"><span>Информация</span></div>
                        </div>
                        <div class="wrapper-button" onclick="krpanoscreenshot();">
                            <span class="icon-ic_share wrapper-button__icon " data-pannel="sharePannel"></span>
                            <div class="dubai360-tooltip"><span>Поделиться</span></div>
                        </div>
                        <div class="wrapper-button" onclick="krpanoautorotate();">
                            <span class="icon-ic_autoplay wrapper-button__icon "></span>
                            <div class="dubai360-tooltip"><span>Тур режим</span></div>
                        </div>
                        <div class="wrapper-button" onclick="krpanofullscreen()">
                            <span class="icon-ic_fullscreen wrapper-button__icon "></span>
                            <div class="dubai360-tooltip"><span>Полный экран</span></div>
                        </div>

                        <div class="wrapper-button">
                            <span class="icon-ic_eye wrapper-button__icon " data-pannel="ProjectionsPannel"></span>
                            <div class="dubai360-tooltip"><span>Режимы просмотра</span></div>
                        </div>
                    </div>
                    <div class="dubai360-header__logo-languaje">
                        <div class="language-switcher">
                            <div class="dropdown-wrapper">
                                <select name="select" class="dropdown">
                                    <option value="en">RU</option>
                                    <option value="ru">EN</option>
                                    <option value="uz">UZ</option>
                                </select>
                                <img src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jaGV2cm9uPC90aXRsZT48cG9seWdvbiBjbGFzcz0iY2xzLTEiIHBvaW50cz0iMTIgMTQgOSAxMSAxNSAxMSAxMiAxNCIvPjwvc3ZnPg==" class="dropdown-chevronImg">
                            </div>
                        </div>
                    </div>
                </header>
                {{--<div class="customBtns">
                    <ul>
                        <li class="icon-plus"></li>
                        <li class="icon-minus"></li>
                        <li class="icon-left"></li>
                        <li class="icon-right"></li>
                        <li class="icon-up"></li>
                        <li class="icon-down"></li>
                    </ul>
                </div>--}}
                <div id="pano" style="width:100%;height:100%;"></div>
                <footer class="dubai360-footer">
                    <div class="wrapper-button"><span class="icon-ic_aerial wrapper-button__icon "></span></div>
                    <div class="wrapper-button"><span class="icon-ic_explore wrapper-button__icon " data-pannel="explorePannel"></span></div>
                    <div class="wrapper-button"><span class="icon-ic_info wrapper-button__icon " data-pannel="infoPannel"></span></div>
                    <div class="wrapper-button"><span class="icon-ic_glass wrapper-button__icon " data-pannel="search"></span></div>
                    <div class="wrapper-button"><span class="icon-ic_share wrapper-button__icon " data-pannel="sharePannel"></span></div>
                    <div class="wrapper-button"><span class="icon-ic_360 wrapper-button__icon " data-pannel="gyroPannel"></span></div>
                </footer>
                <div class="wrapper-panel  top right sharePannel hidden expand">
                    <img class="wrapper-panel-close" src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                    <div class="sharePanel">
                        <div class="sharePanel__title"><span>Поделиться</span></div>

                        <div class="sharePanel__screenshot">
                            <div class="loading2" id="loading2" >
                                <div class="loader2">
                                    <div class="dot2"></div>
                                    <div class="dot2"></div>
                                    <div class="dot2"></div></div>
                                </div>

                                <img src="" id="krpanoscreenshot" style="display: none"></div>
                        <div class="sharePanel__social">
                            <ul class="sharePanel__social__icons">
                                <li class="socialnetwork-icon">
                                    <div role="button" tabindex="0" class="SocialMediaShareButton SocialMediaShareButton--facebook">
                                        <div style="width: 40px; height: 40px;">
                                            <img src="/storage/socialnetworks/facebook.png" alt="facebook share" />
                                        </div>
                                    </div>
                                </li>
                                 <li class="socialnetwork-icon">
                                    <div role="button" tabindex="0" class="SocialMediaShareButton SocialMediaShareButton--telegram">
                                        <div style="width: 40px; height: 40px;">
                                           <img src="/storage/socialnetworks/telegram.png" alt="telegram share" />
                                        </div>
                                    </div>
                                </li>

                                <li class="socialnetwork-icon">
                                    <div role="button" tabindex="0" class="SocialMediaShareButton SocialMediaShareButton--whatsapp">
                                        <div style="width: 40px; height: 40px;">
                                            <img src="/storage/socialnetworks/whatsapp.png" alt="whatsapp share" />
                                        </div>
                                    </div>
                                </li>

                                <li class="socialnetwork-icon">
                     <div role="button" tabindex="0" class="SocialMediaShareButton SocialMediaShareButton--mail">
                                        <div style="width: 40px; height: 40px;">
                                            <img src="/storage/socialnetworks/mail.png" alt="mail share" />
                                        </div>
                                    </div>


                    </li>
                            </ul>
                            <div class="sharePanel__social__share">
                                <div class="sharePanel__social__share__url"><img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4NCjxzdmcgd2lkdGg9IjE3cHgiIGhlaWdodD0iMTdweCIgdmlld0JveD0iMCAwIDE3IDE3IiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPg0KICAgIDwhLS0gR2VuZXJhdG9yOiBTa2V0Y2ggNDguMiAoNDczMjcpIC0gaHR0cDovL3d3dy5ib2hlbWlhbmNvZGluZy5jb20vc2tldGNoIC0tPg0KICAgIDx0aXRsZT5JY29ucy9pY19saW5rPC90aXRsZT4NCiAgICA8ZGVzYz5DcmVhdGVkIHdpdGggU2tldGNoLjwvZGVzYz4NCiAgICA8ZGVmcz48L2RlZnM+DQogICAgPGcgaWQ9IkhvbWVfU2hhcmUiIHN0cm9rZT0ibm9uZSIgc3Ryb2tlLXdpZHRoPSIxIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0xNTY4LjAwMDAwMCwgLTQzNS4wMDAwMDApIj4NCiAgICAgICAgPGcgaWQ9Ikdyb3VwIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxNTQ4LjAwMDAwMCwgNjQuMDAwMDAwKSIgZmlsbD0iI0ZGRkZGRiI+DQogICAgICAgICAgICA8ZyBpZD0ibGluayIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMTIuMDAwMDAwLCAzNjQuMDAwMDAwKSI+DQogICAgICAgICAgICAgICAgPGcgaWQ9Ikljb25zL2ljX2xpbmsiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDguMDAwMDAwLCA3LjAwMDAwMCkiPg0KICAgICAgICAgICAgICAgICAgICA8cGF0aCBkPSJNNS41LDE3LjAwMDMgQzQuMDMsMTcuMDAwMyAyLjY0OSwxNi40MjgzIDEuNjExLDE1LjM4OTMgQzAuNTcyLDE0LjM1MTMgMCwxMi45NjkzIDAsMTEuNTAwMyBDMCwxMC4wMzAzIDAuNTcyLDguNjQ5MyAxLjYxMSw3LjYxMTMgTDQuMTExLDUuMTExMyBMNS41MjUsNi41MjUzIEwzLjAyNSw5LjAyNTMgQzIuMzY0LDkuNjg3MyAyLDEwLjU2NTMgMiwxMS41MDAzIEMyLDEyLjQzNTMgMi4zNjQsMTMuMzEzMyAzLjAyNSwxMy45NzUzIEM0LjM0OCwxNS4yOTczIDYuNjUyLDE1LjI5NzMgNy45NzUsMTMuOTc1MyBMMTAuNDc1LDExLjQ3NTMgTDExLjg4OSwxMi44ODkzIEw5LjM4OSwxNS4zODkzIEM4LjM1MSwxNi40MjgzIDYuOTcsMTcuMDAwMyA1LjUsMTcuMDAwMyBaIE0xMC4wODU5LDUuNTAwMyBDMTAuNDc2OSw1LjEwOTMgMTEuMTA4OSw1LjEwOTMgMTEuNDk5OSw1LjUwMDMgQzExLjg5MDksNS44OTEzIDExLjg5MDksNi41MjQzIDExLjQ5OTksNi45MTQzIEw2LjkxMzksMTEuNTAwMyBDNi41MjI5LDExLjg5MTMgNS44OTA5LDExLjg5MTMgNS40OTk5LDExLjUwMDMgQzUuMTA4OSwxMS4xMDkzIDUuMTA4OSwxMC40NzczIDUuNDk5OSwxMC4wODYzIEwxMC4wODU5LDUuNTAwMyBaIE0xMi44ODg3LDExLjg4OSBMMTEuNDc0NywxMC40NzUgTDEzLjk3NDcsNy45NzUgQzE0LjYzNTcsNy4zMTQgMTQuOTk5Nyw2LjQzNSAxNC45OTk3LDUuNSBDMTQuOTk5Nyw0LjU2NSAxNC42MzU3LDMuNjg2IDEzLjk3NDcsMy4wMjUgQzEyLjY1MjcsMS43MDMgMTAuMzQ3NywxLjcwMyA5LjAyNTcsMy4wMjUgTDYuNTI1Nyw1LjUyNSBMNS4xMTE3LDQuMTExIEw3LjYxMTcsMS42MTEgQzguNjQ5NywwLjU3MiAxMC4wMzE3LDAgMTEuNDk5NywwIEMxMi45Njg3LDAgMTQuMzUwNywwLjU3MiAxNS4zODg3LDEuNjExIEMxNi40Mjc3LDIuNjUgMTYuOTk5Nyw0LjAzMSAxNi45OTk3LDUuNSBDMTYuOTk5Nyw2Ljk3IDE2LjQyNzcsOC4zNTEgMTUuMzg4Nyw5LjM4OSBMMTIuODg4NywxMS44ODkgWiIgaWQ9ImljX2xpbmsiPjwvcGF0aD4NCiAgICAgICAgICAgICAgICA8L2c+DQogICAgICAgICAgICA8L2c+DQogICAgICAgIDwvZz4NCiAgICA8L2c+DQo8L3N2Zz4="></div>
                                <input type="text" value="" id="previewlinkurlshare" readonly="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hotspotPreview-wrapper" style="display: none"><div id="uzb360preview" class="hotspotPreview right" style="left: 1001.06px; top: 240.132px;"><div class="hotspotPreview__innerWrapper"><div class="hotspotPreview__img"><img src="" class="hotspotPreview__img--scene uzbhotspotimg"></div><div class="hotspotPreview__icon-category"><img src="" class="icon-wrapper__icon--category category-normal uzbhotspoticon" style="background-color: rgb(237, 68, 104);"></div><div class="hotspotPreview__text"></div></div></div></div>
                <div class="wrapper-panel  top left search hidden expand">
                    <img class="wrapper-panel-close" src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                    <div class="searchPanel">
                        <div class="searchPanel__input"><input type="text" class="dubai360-input search-input" placeholder="Поиск"></div>
                        <div class="searchPanel__filtered ">
                            <span class="searchPanel__filtered__title">Фильтр</span>
                            <span class="icon-ic_arrow_up chevron-mobile"></span>
                            <span class="searchPanel__filtered__clear">Сбросить</span>
                        </div>
                        <div class="searchPanel__wrapper-category">
                            <div class="category-wrapper ">
                                <div class="btn_slider_slick">
                                 <span class="icon-ic_arrow_left_active_v2 icon-ic_arrow_left_active_v2_first category-wrapper--arrow is-activated--element slick-arrow slick-disabled"  style="display: block;"></span>
                               <span class="icon-ic_arrow_right__active_v2 icon-ic_arrow_right__active_v2_first category-wrapper--arrow is-activated--element slick-arrow"  style="display: block;"></span></div>




                                <div class="cotegory-slick">

 @foreach($categories as $category)
                         @if($loop->first or $loop->iteration % 9 == 0)           <div class="category-wrapper--category">    @endif

                                        <div class="icon-wrapper fade--in">
                                            <div class="js-icon icon-wrapper__icon" data-category="{{ $category->id }}">
                                                <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(66, 213, 175); margin-right: 10px; margin-left: 10px;"><img src="/storage/cat_icons/{{$category->cat_icon_svg}}"></div>
                                                <span class="icon-wrapper__text">{{ $category->name }}</span>
                                            </div>
                                        </div>
                                    @if($loop->last == true or $loop->iteration % 8 == 0)
                                    </div> @endif
     @endforeach



                                </div>

                            </div>
                        </div>
                        <div class="searchPanel__results"><span class="color-opacity">Найдено 0 результатов</span></div>
                        <div class="searchPanel__button" style="display: none;">Найдено 0 результатов</div>
                        <div class="searchPanel__resultscontainer">
                            <div class="virtualizedGrid__content " style="position: relative;">
                                <div style="overflow: visible; width: 0px;">
                                    <div aria-label="grid" aria-readonly="true" class="ReactVirtualized__Grid" role="grid" tabindex="0" style="box-sizing: border-box; direction: ltr; position: relative; width: 600px; will-change: transform; overflow: hidden auto;">
                                        <div id="searchContainer" class="ReactVirtualized__Grid__innerScrollContainer" role="rowgroup" style="position: relative; display: -webkit-flex; display: -moz-flex; display: -ms-flex; display: -o-flex; display: flex; -webkit-flex-wrap: wrap; -moz-flex-wrap: wrap; -ms-flex-wrap: wrap; -o-flex-wrap: wrap; flex-wrap: wrap;">

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
                                <img class="wrapper-panel-close search-close" src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                                <div class="resultsPanel">
                                    <div class="resultsPanel__tittle"><span>Results found</span></div>
                                    <div class="resultsPanel__resultsContainer" style="position: relative;">
                                        <div style="overflow: visible; height: 0px;">
                                            <div class="virtualizedGrid__content " style="position: relative;">
                                                <div style="overflow: visible;">
                                                    <div aria-label="grid" aria-readonly="true" class="ReactVirtualized__Grid" role="grid" tabindex="0" style="box-sizing: border-box; direction: ltr; height: 507px; position: relative; width: 372px; will-change: transform; overflow: auto;">
                                                        <div id="searchContainerMobile" class="ReactVirtualized__Grid__innerScrollContainer" role="rowgroup">

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
                <div class="wrapper-panel feedbackPannel top left hidden expand">
                    <img class="wrapper-panel-close" src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                    <form id="feedbackForm" class="feedbackPanel" novalidate="">
                        {{ csrf_field() }}
                        <div class="feedbackPanel__title"><span>Feedback</span></div>
                        <div class="feedbackPanel__message"><span>Please tell us what you think and help us improve. Any kind of feedback is highly appreciated!</span></div>
                        <div class="feedbackPanel__wrapper-inputs">
                            <div class="feedbackPanel__wrapper-inputs--dropdown">
                                <div class="dropdown-wrapper">
                                    <select name="select" class="dropdown">
                                        <option value="BUG_REPORT">Bug report</option>
                                        <option value="PRESS_ENQUIRY">Press enquiry</option>
                                        <option value="FEATURE_REQUEST">Feature request</option>
                                        <option value="NEW_CONTENT_REQUEST">New content request</option>
                                        <option value="OTHER_FEEDBACK_QUESTIONS">Other feedback or questions</option>
                                    </select>
                                    <img src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jaGV2cm9uPC90aXRsZT48cG9seWdvbiBjbGFzcz0iY2xzLTEiIHBvaW50cz0iMTIgMTQgOSAxMSAxNSAxMSAxMiAxNCIvPjwvc3ZnPg==" class="dropdown-chevronImg">
                                </div>
                            </div>
                        </div>
                        <div class="feedbackPanel__wrapper-inputs"><input type="email" name="email" class="feedbackPanel__wrapper-inputs--input" placeholder="Your email address" required=""></div>
                        <div class="feedbackPanel__wrapper-inputs "><textarea name="message" class="feedbackPanel__wrapper-inputs--textarea" placeholder="Your message" required=""></textarea></div>
                        <div class="feedbackPanel__buttons">
                            <button type="button" class="btn btn--disabled close-btn">
                                <span>Cancel</span>
                            </button>
                            <button class="btn btn--normal">
                                <span>Send</span>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="wrapper-panel  top left helpPannel hidden expand">
                    <img class="wrapper-panel-close" src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                    <div class="helpPanel" style="overflow: hidden;">
                        <div id="tab1" class="section-help">
                            <div class="section-help__content">
                                <span class="section-help__content__title"><span>Welcome</span></span>
                                <div class="section-help__content__message">
                                    <div class="section-help__content__message__text">
                                        <p><span>Вращайте и масштабируйте изображение с помощью элементов управления внизу, мыши или сенсорного экрана.
                                        </span></p>
                                        <p><span>Используйте горячие точки на изображении для навигации между различными фотографиями и видео. Вы также можете перемещаться с помощью карты или категорий.</span></p>
                                        <p><span>По вопросам сотрудничества:<br> 
                                        +998971310023
                                        </span></p>
                                    </div>
                                    <img src="assets/image_mouse.cec9e28c.png">
                                </div>
                            </div>
                        </div>
                        <div id="tab2" class="section-help" style="display: none;">
                                                        <div class="section-help__content">
                                <span class="section-help__content__title"><span>Подсказки</span></span>
                                <div class="category-wrapper-mobile">
                                    <div class="section-help__content__icons--controls">
                                        <div class="icon-wrapper">
                                            <span class="icon-ic_aerial icon-wrapper__icon--controls"></span>
                                            <div class="icon-wrapper__text"><span>Перейти к небу города</span></div>
                                        </div>
                                        <div class="icon-wrapper">
                                            <span class="icon-ic_explore icon-wrapper__icon--controls"></span>
                                            <div class="icon-wrapper__text"><span>Перейти в карту города</span></div>
                                        </div>
                                        <div class="icon-wrapper">
                                            <span class="icon-ic_share icon-wrapper__icon--controls"></span>
                                            <div class="icon-wrapper__text"><span>Поделиться своим видом</span></div>
                                        </div>
                                        <div class="icon-wrapper">
                                            <span class="icon-ic_configuration icon-wrapper__icon--controls"></span>
                                            <div class="icon-wrapper__text"><span>Change video quality and speed</span></div>
                                        </div>
                                        <div class="icon-wrapper">
                                            <span class="icon-ic_comment icon-wrapper__icon--controls"></span>
                                            <div class="icon-wrapper__text"><span> Оставьте свои пожелания или отзыв</span></div>
                                        </div>
                                        <div class="icon-wrapper">
                                            <span class="icon-ic_glass icon-wrapper__icon--controls"></span>
                                            <div class="icon-wrapper__text"><span>Поиск панорам по названию или категории</span></div>
                                        </div>
                                        <div class="icon-wrapper">
                                            <span class="icon-ic_info icon-wrapper__icon--controls"></span>
                                            <div class="icon-wrapper__text"><span>Показать информацию</span></div>
                                        </div>
                                        <div class="icon-wrapper">
                                            <span class="icon-ic_autoplay icon-wrapper__icon--controls"></span>
                                            <div class="icon-wrapper__text"><span>Включить автоматический режим тура</span></div>
                                        </div>
                                        <div class="icon-wrapper">
                                            <span class="icon-ic_eye icon-wrapper__icon--controls"></span>
                                            <div class="icon-wrapper__text"><span>Изменить вид проекции</span></div>
                                        </div>
                                        <div class="icon-wrapper">
                                            <span class="icon-ic_floorplan icon-wrapper__icon--controls"></span>
                                            <div class="icon-wrapper__text"><span>Посмотреть план этажей</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab3" class="section-help" style="display: none;">
                            <div class="section-help__content">
                                <span class="section-help__content__title"><span>Categories</span></span>
                                <div class="category-wrapper-mobile">
                                    <div class="category-wrapper ">
                                        <span class="icon-ic_arrow_left_active_v2 icon-ic_arrow_left_active_v2_sec category-wrapper--arrow is-activated--element"></span>
                                        <div class="cotegory-slick_sec">
                                            <div class="category-wrapper--category">
                                                <div class="icon-wrapper fade--in">
                                                    <div class="icon-wrapper__icon">
                                                        <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(66, 213, 175); margin-right: 10px; margin-left: 10px;"><img src="https://d360-cdn-prod.azureedge.net/assets/a660166c-2124-4384-9db7-263868b2054d.svg"></div>
                                                        <span class="icon-wrapper__text">Sports &amp; Recreation</span>
                                                    </div>
                                                </div>
                                                <div class="icon-wrapper fade--in">
                                                    <div class="icon-wrapper__icon">
                                                        <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(170, 101, 229); margin-right: 10px; margin-left: 10px;"><img src="https://d360-cdn-prod.azureedge.net/assets/c4716106-b3e8-4e4b-a802-638f37ab5532.svg"></div>
                                                        <span class="icon-wrapper__text">Aerial</span>
                                                    </div>
                                                </div>
                                                <div class="icon-wrapper fade--in">
                                                    <div class="icon-wrapper__icon">
                                                        <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(140, 85, 36); margin-right: 10px; margin-left: 10px;"><img src="https://d360-cdn-prod.azureedge.net/assets/f2e8db22-b04a-4a36-9541-bfce68047d8b.svg"></div>
                                                        <span class="icon-wrapper__text">Transports</span>
                                                    </div>
                                                </div>
                                                <div class="icon-wrapper fade--in">
                                                    <div class="icon-wrapper__icon">
                                                        <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(7, 145, 208); margin-right: 10px; margin-left: 10px;"><img src="https://d360-cdn-prod.azureedge.net/assets/bf94159a-0df2-4746-a72a-872a1ecbaa5a.svg"></div>
                                                        <span class="icon-wrapper__text">General</span>
                                                    </div>
                                                </div>
                                                <div class="icon-wrapper fade--in">
                                                    <div class="icon-wrapper__icon">
                                                        <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(237, 68, 104); margin-right: 10px; margin-left: 10px;"><img src="https://d360-cdn-prod.azureedge.net/assets/82d3fa8f-cede-4ef2-bd7d-a14eda2a176e.svg"></div>
                                                        <span class="icon-wrapper__text">Shopping</span>
                                                    </div>
                                                </div>
                                                <div class="icon-wrapper fade--in">
                                                    <div class="icon-wrapper__icon">
                                                        <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(67, 151, 101); margin-right: 10px; margin-left: 10px;"><img src="https://d360-cdn-prod.azureedge.net/assets/5a415d97-77ac-420a-a189-bbde30ce14b9.svg"></div>
                                                        <span class="icon-wrapper__text">Culture &amp; Museum</span>
                                                    </div>
                                                </div>
                                                <div class="icon-wrapper fade--in">
                                                    <div class="icon-wrapper__icon">
                                                        <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(205, 171, 53); margin-right: 10px; margin-left: 10px;"><img src="https://d360-cdn-prod.azureedge.net/assets/330f2921-c1e9-4654-9c25-e6a439f313e4.svg"></div>
                                                        <span class="icon-wrapper__text">Rooftop</span>
                                                    </div>
                                                </div>
                                                <div class="icon-wrapper fade--in">
                                                    <div class="icon-wrapper__icon">
                                                        <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(2, 180, 227); margin-right: 10px; margin-left: 10px;"><img src="https://d360-cdn-prod.azureedge.net/assets/855eeadb-8821-495a-9ad9-78e5975addb4.svg"></div>
                                                        <span class="icon-wrapper__text">Hotel</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="category-wrapper--category">
                                                <div class="icon-wrapper fade--in">
                                                    <div class="icon-wrapper__icon">
                                                        <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(0, 173, 159); margin-right: 10px; margin-left: 10px;"><img src="https://d360-cdn-prod.azureedge.net/assets/447aa566-4e20-4a69-80b1-80dddd5fba9a.svg"></div>
                                                        <span class="icon-wrapper__text">Ultimate Luxury</span>
                                                    </div>
                                                </div>
                                                <div class="icon-wrapper fade--in">
                                                    <div class="icon-wrapper__icon">
                                                        <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(241, 107, 36); margin-right: 10px; margin-left: 10px;"><img src="https://d360-cdn-prod.azureedge.net/assets/9e042238-2ddf-4529-aa63-96c7ace4409f.svg"></div>
                                                        <span class="icon-wrapper__text">Food &amp; Beverages</span>
                                                    </div>
                                                </div>
                                                <div class="icon-wrapper fade--in">
                                                    <div class="icon-wrapper__icon">
                                                        <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(0, 71, 186); margin-right: 10px; margin-left: 10px;"><img src="https://d360-cdn-prod.azureedge.net/assets/a9ddd1e8-b753-437d-ae15-cc8895c06985.svg"></div>
                                                        <span class="icon-wrapper__text">Night</span>
                                                    </div>
                                                </div>
                                                <div class="icon-wrapper fade--in">
                                                    <div class="icon-wrapper__icon">
                                                        <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(232, 189, 81); margin-right: 10px; margin-left: 10px;"><img src="https://d360-cdn-prod.azureedge.net/assets/2c881895-4bbb-468a-b9e4-53c1f3974be8.svg"></div>
                                                        <span class="icon-wrapper__text">Entertainment</span>
                                                    </div>
                                                </div>
                                                <div class="icon-wrapper fade--in">
                                                    <div class="icon-wrapper__icon">
                                                        <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(103, 83, 193); margin-right: 10px; margin-left: 10px;"><img src="https://d360-cdn-prod.azureedge.net/assets/1097aae0-893a-414a-878a-cce9ea5878a7.svg"></div>
                                                        <span class="icon-wrapper__text">Theme Park</span>
                                                    </div>
                                                </div>
                                                <div class="icon-wrapper fade--in">
                                                    <div class="icon-wrapper__icon">
                                                        <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(67, 151, 101); margin-right: 10px; margin-left: 10px;"><img src="https://d360-cdn-prod.azureedge.net/assets/c2421fc9-236b-4da0-8486-1c12e820906f.svg"></div>
                                                        <span class="icon-wrapper__text">Park</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="icon-ic_arrow_right__active_v2 icon-ic_arrow_right__active_v2_sec category-wrapper--arrow is-activated--element"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
{{--                        <div class="pagination">--}}
{{--                            <ul>--}}
{{--                                <li class="pagination__wrapper is-activated--categories" data-tab="tab1">--}}
{{--                                    <div><span class="item">1</span></div>--}}
{{--                                </li>--}}
{{--                                 <li class="pagination__wrapper" data-tab="tab2">--}}
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
                    <img class="wrapper-panel-close" src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                    <div class="infoPanel">
                        <div class="infoPanel__current-categories">
                            <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(237, 68, 104);"><img src="/storage/cat_icons/{{$location->cat_icon_svg}}"></div>
                            <div class="clock_time"> <div class="infoPanel__title" id="location_name2">{{ $location->name }}</div></div>


                        </div>
                        <div class="time_data">      @isset($location->working_hours)
                       <div class="clock">
                          <div class="clock_icon"><img src="/storage/socialnetworks/clock.png"></div>
                          <span id="vremyaraboti">{{$location->working_hours}}2</span>
                      </div>@endisset
                            @isset($location->number)
                       <div class="numberr">
                           <div class="clock_icon"><img src="/storage/socialnetworks/smartphone.png"></div>
                          <div id="location_number">{{ $location->number}}</div>
                      </div>
                    @endisset
                          </div>
                     <!--  <div class="infoPanel__title">{{ $location->name }}</div> -->
                      <!-- <div class="infoPanel__title2">{{ $location->number}}</div> -->
    @isset($location->description)
                      <div class="infoPanel__description">
                          <div class="infoPanel__description__message"><span id="location_description">{{$location->description}}</span></div>
                      </div>
                    @endisset
                      @isset($location->address)


                      <div class="time_data">
                       <div class="clock">
                          <div class="clock_icon"><img src="/storage/socialnetworks/placeholder.png"></div>
                          <span id="location_adress">{{$location->address}}</span>
                      </div>

                          </div>    @endisset
                      <ul class="sharePanel__social__icons" style="    width: 200px;">
                          @isset($location->facebook)
                              <li class="socialnetwork-icon">
                                  <a href="{{$location->facebook}}" target="_blank">
                                      <div style="width: 40px; height: 40px;">
                                          <img src="/storage/socialnetworks/facebook.png" alt="facebook share" />
                                      </div>
                                  </a>
                              </li>
                              @endisset
                              @isset($location->telegram)
                               <li class="socialnetwork-icon">
                                  <a href="{{$location->telegram}}" target="_blank">
                                      <div style="width: 40px; height: 40px;">
                                         <img src="/storage/socialnetworks/telegram.png" alt="telegram share" />
                                      </div>
                                  </a>
                              </li>
                             @endisset
                             @isset($location->instagram)
                              <li class="socialnetwork-icon">
                                    <a href="{{$location->instagram}}" target="_blank">
                                      <div style="width: 40px; height: 40px;">
                                          <img src="/storage/socialnetworks/instagram.png" alt="whatsapp share" />
                                      </div>
                                  </a>
                              </li>
                             @endisset

                          </ul>
                        <div class="virtualizedGrid__otherLocation">
                            <div class="virtualizedGrid__title"><span>Другие локации</span></div>
                            <div class="virtualizedGrid__listContainer">
                                <div class="virtualizedGrid__content slick-block" style="position: relative;">
                                  @foreach($otherlocations as $i=> $otherlocation)
                                    <div class="listItem-wrapper" onclick="loadpano('uzbekistan:{{$otherlocation->id}}', {{$i}}, '{{$otherlocation->slug}}')">
                                        <div class="listItem">
                                            <div class="listItem__img"><img src="/storage/panoramas/unpacked/{{$otherlocation->img}}/thumb.jpg" class="listItem__img--scene"></div>
                                            <div class="listItem__icon-category">
                                                <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(2, 180, 227);"><img src="/storage/cat_icons/{{$otherlocation->cat_icon_svg}}"></div>

                                            </div>
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
                <div class="wrapper-panel  top left floorplanPanel hidden expand">
                    <div class="wrapper-panel--main-container">
                        <span class="wrapper-panel-expand icon-ic_doble_arrow_left"></span>
                        <div class="floorplan fade--in">
                            <span class="floorplan__recommended__title"><span>You may also like...</span></span>
                            <div class="floorplan__listContainer">
                                <div class="virtualizedGrid__content " style="position: relative;">
                                    <div style="overflow: visible; height: 0px; width: 0px;">
                                        <div aria-label="grid" aria-readonly="true" class="ReactVirtualized__Grid" role="grid" tabindex="0" style="box-sizing: border-box; direction: ltr; height: 546px; position: relative; width: 273px; will-change: transform; overflow: hidden auto;">
                                            <div class="ReactVirtualized__Grid__innerScrollContainer" role="rowgroup" style="width: 240px; height: 3900px; max-width: 240px; max-height: 3900px; overflow: hidden; position: relative;">
                                                <div class="listItem-wrapper" style="height: 260px; left: 0px; position: absolute; top: 0px; width: 240px;">
                                                    <div class="listItem" style="width: 224px; height: 244px;">
                                                        <div class="listItem__img"><img src="https://d360-cdn-prod.azureedge.net/b69d5230-4abc-4a1c-92b7-f90402a09872/input.tiles/thumb_200_100.jpg" class="listItem__img--scene"></div>
                                                        <div class="listItem__icon-category">
                                                            <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(237, 68, 104);"><img src="https://d360-cdn-prod.azureedge.net/assets/82d3fa8f-cede-4ef2-bd7d-a14eda2a176e.svg"></div>
                                                        </div>
                                                        <div class="listItem__text">
                                                            <div><span><span><span>01 Mall Of The Emirates -</span><br><span>Ground Floor - Al Halabi</span></span><span style="position: fixed; visibility: hidden; top: 0px; left: 0px;">…</span></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="listItem-wrapper" style="height: 260px; left: 0px; position: absolute; top: 260px; width: 240px;">
                                                    <div class="listItem" style="width: 224px; height: 244px;">
                                                        <div class="listItem__img"><img src="https://d360-cdn-prod.azureedge.net/9f068e63-826d-4a07-a4c8-60d4a40c1c0e/input.tiles/thumb_200_100.jpg" class="listItem__img--scene"></div>
                                                        <div class="listItem__icon-category">
                                                            <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(237, 68, 104);"><img src="https://d360-cdn-prod.azureedge.net/assets/82d3fa8f-cede-4ef2-bd7d-a14eda2a176e.svg"></div>
                                                        </div>
                                                        <div class="listItem__text">
                                                            <div><span><span><span>Mall Of The Emirates -</span><br><span>Ground Floor - Burj Al</span><br><span>Hamam</span></span><span style="position: fixed; visibility: hidden; top: 0px; left: 0px;">…</span></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="listItem-wrapper" style="height: 260px; left: 0px; position: absolute; top: 520px; width: 240px;">
                                                    <div class="listItem" style="width: 224px; height: 244px;">
                                                        <div class="listItem__img"><img src="https://d360-cdn-prod.azureedge.net/06e41b36-9d23-4a3a-9913-aff43177fbeb/input.tiles/thumb_200_100.jpg" class="listItem__img--scene"></div>
                                                        <div class="listItem__icon-category">
                                                            <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(237, 68, 104);"><img src="https://d360-cdn-prod.azureedge.net/assets/82d3fa8f-cede-4ef2-bd7d-a14eda2a176e.svg"></div>
                                                        </div>
                                                        <div class="listItem__text">
                                                            <div><span><span><span>Mall Of The Emirates -</span><br><span>Ground Floor - Atrium</span></span><span style="position: fixed; visibility: hidden; top: 0px; left: 0px;">…</span></span></div>
                                                        </div>
                                                    </div>
                                                </div>
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
                    <div style="width: calc(100% - 289px);">
                        <div class="floorplan-viewer">
                            <div class="floorplan-viewer__header">
                                <span class="floorplan-viewer__header__name">Mall Of The Emirates, L1</span>
                                <div class="floorplan-viewer__header__actions">
                                    <div class="floorplan-viewer__header__img"><span class="icon-ic_mouse floorplan-viewer__header__img--icon"></span><span class="floorplan-viewer__header__img--text"><span>Zoom in for more hotspots</span></span></div>
                                    <div class="icon-wrapper"><span class="icon-ic_close close"></span></div>
                                </div>
                            </div>

                            @php
                                $panoramas = json_decode($location->panorama);
                            @endphp

                            <div class="krpano-floorplan">
                                <div id="floor-krpano" class="krpano">
                                    @foreach($panoramas as $i => $floor)
                                        @if(!empty($floor->plan))
                                            <div id="floorplan-tab{{ $i }}" class="floorplan-tab" tabindex="-1" style="display: none;">
                                                <div class="plan">
                                                    <img class="planClass" style="border: 1px dotted black;" src="/storage/{{ $floor->plan }}">

                                                    @foreach($floor->panoramas as $hotspot)
                                                        <span onclick="loadpano('axmad4ik:{{ $location->id }}', {{ $i }});" class="planHotspot" style="position: absolute; left: {{ $hotspot->x }}; top: {{ $hotspot->y }};"></span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                            </div>

                            <ul class="floorplan-viewer__footer">
                                @foreach($panoramas as $i => $floor)
                                    @if(!empty($floor->plan))
                                        <li class="floorplan-viewer__footer__element" data-tab="{{ $i }}">
                                            <img class="floorplan-viewer__footer__element__icon" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4NCjxzdmcgd2lkdGg9IjM0cHgiIGhlaWdodD0iMzNweCIgdmlld0JveD0iMCAwIDM0IDMzIiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPg0KICAgIDwhLS0gR2VuZXJhdG9yOiBTa2V0Y2ggNDguMiAoNDczMjcpIC0gaHR0cDovL3d3dy5ib2hlbWlhbmNvZGluZy5jb20vc2tldGNoIC0tPg0KICAgIDx0aXRsZT5pY19sZXZlbDwvdGl0bGU+DQogICAgPGRlc2M+Q3JlYXRlZCB3aXRoIFNrZXRjaC48L2Rlc2M+DQogICAgPGRlZnM+PC9kZWZzPg0KICAgIDxnIGlkPSJIb21lX0Zsb29ycGxhbl92MiIgc3Ryb2tlPSJub25lIiBzdHJva2Utd2lkdGg9IjEiIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTMxMi4wMDAwMDAsIC0xMDA4LjAwMDAwMCkiPg0KICAgICAgICA8ZyBpZD0iRmxvb3JwbGFuIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSg4LjAwMDAwMCwgNjQuMDAwMDAwKSIgZmlsbD0iI0ZGRkZGRiI+DQogICAgICAgICAgICA8ZyBpZD0ibGV2ZWxzIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgyODguMDAwMDAwLCA5MzYuMDAwMDAwKSI+DQogICAgICAgICAgICAgICAgPGcgaWQ9ImxldmVsc19zZWxlY3QiPg0KICAgICAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMzMsMzguMTc0NSBDMjQuMDI3LDM4LjE3NDUgMTgsMzYuMTA2NSAxOCwzNC4xNzQ1IEMxOCwzMi40NzQ1IDIyLjY3NCwzMC42Njk1IDI5LjkxMiwzMC4yNjE1IEwzMywzMy4zNDk1IEwzNi4wODgsMzAuMjYxNSBDNDMuMzI2LDMwLjY2OTUgNDgsMzIuNDc0NSA0OCwzNC4xNzQ1IEM0OCwzNi4xMDY1IDQxLjk3MywzOC4xNzQ1IDMzLDM4LjE3NDUgTTMzLDEzLjk5OTUgQzM1LjQ4NSwxMy45OTk1IDM3LjUsMTYuMDE0NSAzNy41LDE4LjQ5OTUgQzM3LjUsMjAuOTg1NSAzNS40ODUsMjIuOTk5NSAzMywyMi45OTk1IEMzMC41MTUsMjIuOTk5NSAyOC41LDIwLjk4NTUgMjguNSwxOC40OTk1IEMyOC41LDE2LjAxNDUgMzAuNTE1LDEzLjk5OTUgMzMsMTMuOTk5NSBNMzcuOTU2LDI4LjM5MzUgTDQwLjQyNSwyNS45MjQ1IEM0NC41MiwyMS44MzA1IDQ0LjUyLDE1LjE2OTUgNDAuNDI1LDExLjA3NTUgQzM4LjQ0MSw5LjA5MjUgMzUuODA1LDcuOTk5NSAzMyw3Ljk5OTUgQzMwLjE5NSw3Ljk5OTUgMjcuNTU5LDkuMDkyNSAyNS41NzQsMTEuMDc1NSBDMjEuNDgsMTUuMTY5NSAyMS40OCwyMS44MzA1IDI1LjU3NSwyNS45MjQ1IEwyOC4wNDQsMjguMzkzNSBDMjEuNzU0LDI4Ljk1NDUgMTYsMzAuNjY0NSAxNiwzNC4xNzQ1IEMxNiwzOC42MDM1IDI1LjE1OCw0MC4xNzQ1IDMzLDQwLjE3NDUgQzQwLjg0Miw0MC4xNzQ1IDUwLDM4LjYwMzUgNTAsMzQuMTc0NSBDNTAsMzAuNjY0NSA0NC4yNDYsMjguOTU0NSAzNy45NTYsMjguMzkzNSIgaWQ9ImljX2xldmVsIj48L3BhdGg+DQogICAgICAgICAgICAgICAgPC9nPg0KICAgICAgICAgICAgPC9nPg0KICAgICAgICA8L2c+DQogICAgPC9nPg0KPC9zdmc+">
                                            <span class="floorplan-viewer__footer__element__name">{{ $floor->name }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="wrapper-panel  top right ProjectionsPannel hidden expand">
                    <div class="projection">
                        <div class="title-projection"><span>Projection</span></div>
                        <ul>
                            <li class="selected" onclick="skin_view_normal()"><span>Normal</span></li>
                            <li onclick="skin_view_littleplanet()"><span>Little Planet</span></li>
                            <li onclick="skin_view_fisheye()"><span>Fisheye</span></li>
                            <li onclick="skin_view_stereographic()"><span>Stereographic</span></li>
                            <li onclick="skin_view_architectural()"><span>Architectural</span></li>
                        </ul>
                    </div>
                </div>
                <div class="wrapper-panel  top left gyroPannel hidden expand">
                    <img class="wrapper-panel-close" src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                    <div class="gyro">
                        <div class="gyro__title"><span>360º Mode</span></div>
                        <div class="gyro__icon"><img src="data:image/png;base64,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"></div>
                        <div class="gyro__message">
                            <p><span>Rotate the image using the mobile gyroscope</span></p>
                        </div>
                        <div class="gyro__button"><button class="btn btn--normal"><span>OK</span></button></div>
                        <div class="gyro__checkbox">
                            <div>
                                <div class="checkbox-wrapper">
                                    <div class="checkbox "></div>
                                </div>
                            </div>
                            <div class="gyro__checkbox--text"><span>Don’t show this message again</span></div>
                        </div>
                    </div>
                </div>
                <span class="">
            <div class="kak2 sad" style="opacity: 1;">
              <div class="wrapper-panel explorePannel top left hidden fullScreenPanel">
                <div class="SplitPane  horizontal " style="display: flex; flex: 1 1 0%; height: 100%; position: absolute; outline: none; overflow: hidden; user-select: text; bottom: 0px; flex-direction: column; min-height: 100%; top: 0px; width: 100%;">
                  <div class="Pane horizontal Pane1  " >
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
                      <div class="wrapper-slider__slider"><span class="icon-ic_arrow_up"></span><span class="icon-ic_arrow_down"></span></div>
                    </div>
                    <div class="mybtn wrapper-button"><span>Filter</span><img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4NCjxzdmcgd2lkdGg9IjIwcHgiIGhlaWdodD0iMjBweCIgdmlld0JveD0iMCAwIDIwIDIwIiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPg0KICAgIDwhLS0gR2VuZXJhdG9yOiBTa2V0Y2ggNDguMiAoNDczMjcpIC0gaHR0cDovL3d3dy5ib2hlbWlhbmNvZGluZy5jb20vc2tldGNoIC0tPg0KICAgIDx0aXRsZT5QYWdlIDE8L3RpdGxlPg0KICAgIDxkZXNjPkNyZWF0ZWQgd2l0aCBTa2V0Y2guPC9kZXNjPg0KICAgIDxkZWZzPjwvZGVmcz4NCiAgICA8ZyBpZD0iSG9tZV9leHBsb3JlIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSIgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMzI0LjAwMDAwMCwgLTM5MC4wMDAwMDApIj4NCiAgICAgICAgPGcgaWQ9ImxldmVsc19zZWxlY3QiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDI2My4wMDAwMDAsIDM4NC4wMDAwMDApIiBmaWxsPSIjRkZGRkZGIj4NCiAgICAgICAgICAgIDxwYXRoIGQ9Ik03OSwxNy4xODQgTDc5LDYgTDc3LDYgTDc3LDE3LjE4NCBDNzUuODM4LDE3LjU5NyA3NSwxOC42OTUgNzUsMjAgQzc1LDIxLjMwNSA3NS44MzgsMjIuNDAzIDc3LDIyLjgxNiBMNzcsMjYgTDc5LDI2IEw3OSwyMi44MTYgQzgwLjE2MiwyMi40MDMgODEsMjEuMzA1IDgxLDIwIEM4MSwxOC42OTUgODAuMTYyLDE3LjU5NyA3OSwxNy4xODQgWiBNNzIsNiBMNzAsNiBMNzAsOS4xODQgQzY4LjgzOCw5LjU5NyA2OCwxMC42OTYgNjgsMTIgQzY4LDEzLjMwNCA2OC44MzgsMTQuNDAzIDcwLDE0LjgxNiBMNzAsMjYgTDcyLDI2IEw3MiwxNC44MTYgQzczLjE2MiwxNC40MDMgNzQsMTMuMzA0IDc0LDEyIEM3NCwxMC42OTYgNzMuMTYyLDkuNTk3IDcyLDkuMTg0IEw3Miw2IFogTTY1LDIwLjgxNiBMNjUsMjYgTDYzLDI2IEw2MywyMC44MTYgQzYxLjgzOCwyMC40MDMgNjEsMTkuMzA1IDYxLDE4IEM2MSwxNi42OTYgNjEuODM4LDE1LjU5NyA2MywxNS4xODQgTDYzLDYgTDY1LDYgTDY1LDE1LjE4NCBDNjYuMTYyLDE1LjU5NyA2NywxNi42OTYgNjcsMTggQzY3LDE5LjMwNSA2Ni4xNjIsMjAuNDAzIDY1LDIwLjgxNiBaIiBpZD0iUGFnZS0xIj48L3BhdGg+DQogICAgICAgIDwvZz4NCiAgICA8L2c+DQo8L3N2Zz4=" class="icon-ic_arrow_up"></div>
                  </div>
                  <div class="Pane horizontal Pane2  ">
                    <div class="explore__listContainer">
                      <div class="virtualizedGrid__otherLocation__wrapper mytab mytab1" style="height: 278px;">
                        <div class="virtualizedGrid">
                          <span class="icon-ic_arrow_left_big slick-block2_left"></span>
                          <div class="virtualizedGrid__content " style="position: relative;">
                            <div class="virtualizedGrid__otherLocation__title"><span>Избранные локации</span></div>
                            <div class="ReactVirtualized__Grid slick-block2">
                                @foreach ($isfeatured as $i => $featured)
                              <div class="listItem-wrapper featuredloctionbox"  data-lat="{{$featured->lat}}" data-lng="{{$featured->lng}}" onclick="loadpano('uzbekistan:{{$featured->id}}', {{$i}}, '{{$featured->slug}}')">
                                <div class="listItem">
                                  <div class="listItem__img"><img src="/storage/panoramas/unpacked/{{$featured->img}}/thumb.jpg" class="listItem__img--scene"></div>
                                  <div class="listItem__icon-category">
                                    <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(67, 151, 101);"><img src="/storage/cat_icons/{{$featured->cat_icon_svg}}"></div>
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
                            <div class="virtualizedGrid__otherLocation__title"><span>Новые локации</span></div>
                            <div class="ReactVirtualized__Grid slick-block3">
                              @foreach ($otherlocations as $i => $otherlocation)
                              <div class="listItem-wrapper featuredloctionbox" data-lat="{{$otherlocation->lat}}" data-lng="{{$otherlocation->lng}}" onclick="loadpano('uzbekistan:{{$otherlocation->id}}', {{$i}}, '{{$otherlocation->slug}}')">
                                <div class="listItem">
                                  <div class="listItem__img"><img src="/storage/panoramas/unpacked/{{$otherlocation->img}}/thumb.jpg" class="listItem__img--scene"></div>
                                  <div class="listItem__icon-category">
                                    <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(67, 151, 101);"><img src="/storage/cat_icons/{{$otherlocation->cat_icon_svg}}"></div>
                                  </div>
                                  <div class="listItem__text">
                                    <div><span>{{$otherlocation->name}}</span></div>
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
                      <!-- <div class="virtualizedGrid__otherLocation__wrapper mytab mytab3" style="height: 278px;">
                        <div class="virtualizedGrid">
                          <span class="icon-ic_arrow_left_big slick-block4_left"></span>
                          <div class="virtualizedGrid__content " style="position: relative;">
                            <div class="virtualizedGrid__otherLocation__title">
                                <span>General</span>
                            </div>
                            <div class="ReactVirtualized__Grid slick-block4">
                              <div class="listItem-wrapper">
                                <div class="listItem">
                                  <div class="listItem__img"><img src="https://d360-cdn-prod.azureedge.net/8eb4a333-3f69-4000-9db0-9e1ce503bdc3/input.tiles/thumb_200_100.jpg" class="listItem__img--scene"></div>
                                  <div class="listItem__icon-category">
                                    <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(67, 151, 101);"><img src="https://d360-cdn-prod.azureedge.net/assets/c2421fc9-236b-4da0-8486-1c12e820906f.svg"></div>
                                  </div>
                                  <div class="listItem__text">
                                    <div><span><span><span>23 Dubai Miracle Garden</span><br><span>Walkabout 2017-2018</span></span><span style="position: fixed; visibility: hidden; top: 0px; left: 0px;">…</span></span></div>
                                  </div>
                                </div>
                              </div>
                              <div class="listItem-wrapper">
                                <div class="listItem">
                                  <div class="listItem__img"><img src="https://d360-cdn-prod.azureedge.net/a47644d8-618f-49aa-a448-38d2fdf8ff49/input.tiles/thumb_200_100.jpg" class="listItem__img--scene"></div>
                                  <div class="listItem__icon-category">
                                    <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(67, 151, 101);"><img src="https://d360-cdn-prod.azureedge.net/assets/5a415d97-77ac-420a-a189-bbde30ce14b9.svg"></div>
                                  </div>
                                  <div class="listItem__text">
                                    <div><span><span><span>Saruq Al-Hadid Archeology</span><br><span>Museum, Trade and</span><br><span>Communication</span></span><span style="position: fixed; visibility: hidden; top: 0px; left: 0px;">…</span></span></div>
                                  </div>
                                </div>
                              </div>
                              <div class="listItem-wrapper">
                                <div class="listItem">
                                  <div class="listItem__img"><img src="https://d360-cdn-prod.azureedge.net/c257fd92-e488-467a-9213-6fcfedae485c/input.tiles/thumb_200_100.jpg" class="listItem__img--scene"></div>
                                  <div class="listItem__icon-category">
                                    <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(67, 151, 101);"><img src="https://d360-cdn-prod.azureedge.net/assets/5a415d97-77ac-420a-a189-bbde30ce14b9.svg"></div>
                                  </div>
                                  <div class="listItem__text">
                                    <div><span><span><span>121 Etihad Museum, Postal</span><br><span>History Exhibition, Stamp Salon</span></span><span style="position: fixed; visibility: hidden; top: 0px; left: 0px;">…</span></span></div>
                                  </div>
                                </div>
                              </div>
                              <div class="listItem-wrapper">
                                <div class="listItem">
                                  <div class="listItem__img"><img src="https://d360-cdn-prod.azureedge.net/a47644d8-618f-49aa-a448-38d2fdf8ff49/input.tiles/thumb_200_100.jpg" class="listItem__img--scene"></div>
                                  <div class="listItem__icon-category">
                                    <div class="icon-wrapper__icon--category category-normal" style="background-color: rgb(67, 151, 101);"><img src="https://d360-cdn-prod.azureedge.net/assets/5a415d97-77ac-420a-a189-bbde30ce14b9.svg"></div>
                                  </div>
                                  <div class="listItem__text">
                                    <div><span><span><span>Saruq Al-Hadid Archeology</span><br><span>Museum, Trade and</span><br><span>Communication</span></span><span style="position: fixed; visibility: hidden; top: 0px; left: 0px;">…</span></span></div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="resize-triggers">
                              <div class="expand-trigger">
                                <div style="width: 720px; height: 276px;"></div>
                              </div>
                              <div class="contract-trigger"></div>
                            </div>
                          </div>
                          <span class="icon-ic_arrow_right_big slick-block4_right"></span>
                        </div>
                      </div> -->
                    </div>
                  </div>
                </div>
                <span>
                  <div class="kak2 sad" style="opacity: 1;">
                    <div class="fullScreenPanel fullScreenPanel_filter">
                      <img class="wrapper-panel-close myclose" src="data:image/svg+xml;base64,PHN2ZyBpZD0iRXhwb3J0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMyYTJhMmY7b3BhY2l0eTowLjU7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5pY19jbG9zZTwvdGl0bGU+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIwLjQ4IDQuOTMgMTkuMDcgMy41MiAxMiAxMC41OSA0LjkzIDMuNTIgMy41MiA0LjkzIDEwLjU5IDEyIDMuNTIgMTkuMDcgNC45MyAyMC40OCAxMiAxMy40MSAxOS4wNyAyMC40OCAyMC40OCAxOS4wNyAxMy40MSAxMiAyMC40OCA0LjkzIi8+PC9zdmc+">
                      <div class="filterPanel">
                        <div class="filterPanel__title">
                          <span>Filter by</span>
                        </div>
                        <ul class="filterPanel__options">
                          <li class="active" data-tab="1">
                            <span>Featured locations</span>
                          </li>
                          <li class="" data-tab="2">
                            <span>Recently added</span>
                          </li>
                          <!-- <li class="" data-tab="3">
                            <span>General</span>
                          </li> -->
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
@endsection

@section('scripts')
    <script>
        $('#feedbackForm').on('submit', function(e) {
            e.preventDefault();

            var _this = $(this);

            $.post('/form/1', _this.serialize()).done(function() {
                alert('okay');
            }).fail(function() {
                alert("it's not okay");
            });
        });


        var krpano = null;

        function krpano_onready_callback(krpano_interface) {
            krpano = krpano_interface;

            setTimeout(function() {
                @foreach($krhotspots as $index => $hotspot)
                add_exist_hotspot({{ $hotspot->h }}, {{ $hotspot->v }}, "{{ $hotspot->name }}", "{{$hotspot ->cat_icon_svg}}","{{$hotspot->cat_icon}}","{{$hotspot->img}}","uzbekistan:{{ $hotspot->destination_id }}", {{ $index }}, "{{$hotspot->slug}}");
                @endforeach
            }, 3000);
        }
@if(isset($hotspot))
        $(function(){
            $.get(
              // "/hasFloors/axmad4ik:{{ $hotspot->destination_id }}",
              // onAjaxSuccess
            );

            function onAjaxSuccess(data)
            {
              if(data == 1) {
                $('.icon-ic_floorplan').parent().show();
              } else {
                $('.icon-ic_floorplan').parent().hide();
              }
            }
        });@endif
        function krpanofullscreen () {
            krpano.call("set(fullscreen,true);");
            $('#logo2').css('display', 'block');
            $('#pano1').append(`<div id="logo2" onclick="krpanofullscreenexit()" class="icon-ic_windowed fullScreenIcon" style="display: block; position: absolute; z-index: 99999;"></div>`);
        }
        function krpanofullscreenexit ()  {
            krpano.call("set(fullscreen,false);");
            $('.icon-ic_fullscreen').removeClass('is-active')
          $('#logo2').remove();
        }


function skin_view_fisheye () {

   this.krpano.call("tween(view.vlookat, 0.0, 1.0, easeInOutSine)");
   this.krpano.call("tween(view.fov, 150, distance(150,0.8)");
this.krpano.set("view.architectural", !1);
 this.krpano.set("view.pannini", !1);
 this.krpano.set("view.stereographic", !1);
  this.krpano.call("tween(view.vlookat,       0, 0.5)");
  this.krpano.call("tween(view.fisheye,       1.0, 1)");
   this.krpano.call("tween(view.fovmax,       150, 1)");
   this.krpano.call("tween(view.fov,       150, 0.5)");

}
function krpanoautorotate () {
    krpano.call("switch(autorotate.enabled)");

}
function skin_view_littleplanet () {
         this.krpano.call("tween(view.vlookat, 0.0, 1.0, easeInOutSine)");
   this.krpano.call("tween(view.fov, 150, distance(150,0.8)");
    krpano.call("tween(view.architectural, 0.0, distance(1.0,0.5));");
    krpano.call("tween(view.pannini,       0.0, distance(1.0,0.5));");
    krpano.call("tween(view.distortion,    1.0, distance(1.0,0.8));");
    krpano.call("tween(view.fov,           150, distance(150,0.8));");
    krpano.call("tween(view.vlookat,        90, distance(100,0.8));");
    krpano.call("tween(view.hlookat, calc(view.hlookat + 100.0 + 45.0*random), distance(100,0.8));");
    krpano.call("skin_deeplinking_update_url(1.0);");
}

function krpanoscreenshot () {


        krpano.call("makescreenshot();");


}
  function skin_view_stereographic() {
      this.krpano.call("tween(view.vlookat, 0.0, 1.0, easeInOutSine)");
   this.krpano.call("tween(view.fov, 150, distance(150,0.8)");

    this.krpano.set("view.architectural", !1);
    this.krpano.set("view.pannini", !1);
    this.krpano.set("view.stereographic", !0);
    this.krpano.call("tween(view.vlookat,       0, 0.5)");
    this.krpano.call("tween(view.fovmax,       120, 0.5)");
    this.krpano.call("tween(view.fov,       90, 0.5)");
    this.krpano.call("tween(view.fisheye,       1.0, distance(1.0,0.8))");
}

function skin_view_architectural (){
      this.krpano.call("tween(view.vlookat, 0.0, 1.0, easeInOutSine)");
   this.krpano.call("tween(view.fov, 150, distance(150,0.8)");

    krpano.call("tween(view.architectural, 1.0, distance(1.0,0.5));");
    krpano.call("tween(view.pannini,       0.0, distance(1.0,0.5));");
    krpano.call("tween(view.distortion,    0.0, distance(1.0,0.5));");
  }
  function skin_view_normal (){

    krpano.call("tween(view.architectural, 0.0, distance(1.0,0.5));");
    krpano.call("tween(view.pannini,       0.0, distance(1.0,0.5));");
    krpano.call("tween(view.distortion,    0.0, distance(1.0,0.5));");
  }

        function loadpano(xmlname, index, url)
        {
            if (krpano)
            {
                xmlname = xmlname.split(':')[1];
                var tmp = xmlname;
                xmlname = "/krpano/" + index + '/' + xmlname;
                remove_all_hotspots();
                krpano.call("loadpano(" + xmlname + ", null, MERGE|KEEPBASE|KEEPHOTSPOTS, ZOOMBLEND(1,2,easeInQuad));");
                krpano.call("loadscene('scene1', null, MERGE|KEEPBASE|KEEPHOTSPOTS, ZOOMBLEND(1,2,easeInQuad));");

                xmlname = xmlname.split('/').join(':');
                xmlname = xmlname.replace(':', '/');
                xmlname = xmlname.replace(':', '');


                $.get(
                  "/hasFloors" + xmlname,
                  onAjaxSuccess
                );

                function onAjaxSuccess(data)
                {
                  if(data == 1) {
                    $('.icon-ic_floorplan').parent().show();
                  } else {
                    $('.icon-ic_floorplan').parent().hide();
                  }
                }


                krpano.call("movecamera(0,0);");
history.pushState({
    id: 'homepage'
}, 'Home | My App', '/location/'+url+'');

    $.get('/api/location/' + url).done(function(data) {
$( "#location_name" ).text(data.name);
$( "#location_name2" ).text(data.name);
$( "#vremyaraboti" ).text(data.working_hours);

$( "#location_number" ).text(data.number);
$( "#location_description" ).text(data.description);
$( "#location_adress" ).text(data.address);
    })
                $.get('/api/hotspots/' + tmp).done(function(data) {


                    for(var i = 0; i < data.length; i++) {
                        add_exist_hotspot(data[i].h, data[i].v, data[i].name, data[i].cat_icon_svg, data[i].cat_icon, data[i].img, "uzbekistan:" + data[i].destination_id, i, data[i].slug);
                    }
                });
            }
        }

        function remove_all_hotspots()
        {
            if (krpano)
            {
                krpano.call("loop(hotspot.count GT 0, removehotspot(0) );");
            }
        }







        function add_exist_hotspot(h, v, name,cat_icon_svg, cat_icon, img,  hs_name, index, slug) {
            if (krpano) {

                krpano.call("addhotspot(" + hs_name + ")");
                krpano.set("hotspot[" + hs_name + "].url", "/storage/cat_icons/"+ cat_icon +"");
                krpano.set("hotspot[" + hs_name + "].ath", h);
                krpano.set("hotspot[" + hs_name + "].atv", v);
                r =  h;
                krpano.set("hotspot[" + hs_name + "].scale",  0.35);
                krpano.set("hotspot[" + hs_name + "].edge", "bottom");

                krpano.set("hotspot[" + hs_name + "].onout", function() {
    $( ".hotspotPreview-wrapper" ).hide();
});
                krpano.set("hotspot[" + hs_name + "].onover", function() {

                    $( ".hotspotPreview-wrapper" ).show();
                hotspottext=$('.hotspotPreview__text');
                hotspoticon=$('.uzbhotspoticon');
                hotspotimg= $('.uzbhotspotimg');
                var n = krpano.spheretoscreen(h, v);
                var m = krpano.get("state.position.location");



var link = $('.hotspotPreview__text');

var uzb360preview = $('#uzb360preview');
    var bottomFromVisota = $(document).height() ;
    var bottomFromShirota = $(document).width() ;

    var preview = $('.hotspotPreview ');
var previewx = n.x+50;
var previewxx = n.x-280;
var previewxxx = n.x-120;
var previewy = n.y+30;
var previewyy = n.y-200;
var top = n.y -325;
var bottom = n.y +30;
var left = n.x -118;
var xxx =  bottomFromShirota - (bottomFromShirota-n.x);
var xxxx = bottomFromVisota - (bottomFromVisota-n.y);
if (bottomFromShirota-n.x > 500) {

    preview.css('left', ''+previewx+'px')
    preview.css('top', ''+previewyy+'px')

    uzb360preview.removeClass();
    uzb360preview.addClass('hotspotPreview right');


} else {


    preview.css('left', ''+previewxx+'px')
    preview.css('top', ''+previewyy+'px')
    uzb360preview.removeClass();
    uzb360preview.addClass('hotspotPreview left');
}



if (bottomFromVisota-n.y < 90 && bottomFromShirota-n.x> 150 && xxx>150) {

    preview.css('left', ''+left+'px')
    preview.css('top', ''+top+'px')
     uzb360preview.removeClass();
    uzb360preview.addClass('hotspotPreview bottom');
}

if (xxxx < 254 && bottomFromShirota-n.x> 150 && xxx>150) {

    preview.css('left', ''+left+'px')
    preview.css('top', ''+bottom+'px')
     uzb360preview.removeClass();
    uzb360preview.addClass('hotspotPreview top');
}


                hotspottext.text(name);
                hotspoticon.attr("src","/storage/cat_icons/"+cat_icon_svg+"");
               hotspotimg.attr("src","/storage/panoramas/unpacked/"+img+"/thumb.jpg");
                    });




                krpano.set("hotspot[" + hs_name + "].distorted", false);

                if (krpano.get("device.html5")) {

                    krpano.set("hotspot[" + hs_name + "].onclick", function (hs) {
                        loadpano(hs_name, index, slug);

                    }.bind(null, hs_name));
                }
            }
        }


        function initMap() {


    var location = new google.maps.LatLng({{$curlocation->lat}},{{$curlocation->lng}});

    var map = new google.maps.Map(document.getElementById('map'), {
        center: location,
 clickableIcons: false
    });
    map.setZoom(14);
    var locations = <?php print_r(json_encode($locationscordinate)) ?>;
  $.each( locations, function( index, value ){
    var icon = {
    url:'/storage/cat_icons/'+ value.cat_icon, // url
    scaledSize: new google.maps.Size(50, 50), // scaled size

};
         var marker = new google.maps.Marker({

        position: new google.maps.LatLng(value.lat, value.lng),
        map: map,
         animation: google.maps.Animation.DROP,
          icon: icon,
maphotspotname: value.name,
maphotspoticon: value.cat_icon_svg,
maphotspotimg: value.img
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

        console.log(currentLocation);
        return currentLocation;
    }
      marker.addListener('mouseout', function() {
        $( ".hotspotPreview-wrapper" ).hide();
      })
  marker.addListener('mouseover', function() {
  var maphotspotname = this.maphotspotname;
  var maphotspoticon = this.maphotspoticon;
  var maphotspotimg = this.maphotspotimg;
  $( ".hotspotPreview-wrapper" ).show();
  hotspottext=$('.hotspotPreview__text');
  hotspoticon=$('.uzbhotspoticon');
  hotspotimg= $('.uzbhotspotimg');
  var projection = map.getProjection();
  var markerLocation = marker.getPosition();
  var latmark = $(this).data('lat');
  var lngmark = $(this).data('lng');
  var n = getPixelLocation(marker.getPosition());



  var link = $('.hotspotPreview__text');
  var uzb360preview = $('#uzb360preview');
  var bottomFromVisota = $(document).height() ;
  var bottomFromShirota = $(document).width() ;

  var preview = $('.hotspotPreview ');
  var previewx = n.x+50;
  var previewxx = n.x-280;
  var previewxxx = n.x-120;
  var previewy = n.y+30;
  var previewyy = n.y-200;
  var top = n.y -325;
  var bottom = n.y +30;
  var left = n.x -118;
  var xxx =  bottomFromShirota - (bottomFromShirota-n.x);
  var xxxx = bottomFromVisota - (bottomFromVisota-n.y);
  if (bottomFromShirota-n.x > 500) {

    preview.css('left', ''+previewx+'px')
    preview.css('top', ''+previewyy+'px')

    uzb360preview.removeClass();
    uzb360preview.addClass('hotspotPreview right');


    } else {


    preview.css('left', ''+previewxx+'px')
    preview.css('top', ''+previewyy+'px')
    uzb360preview.removeClass();
    uzb360preview.addClass('hotspotPreview left');
    }



    if (bottomFromVisota-n.y < 90 && bottomFromShirota-n.x> 150 && xxx>150) {

    preview.css('left', ''+left+'px')
    preview.css('top', ''+top+'px')
    uzb360preview.removeClass();
    uzb360preview.addClass('hotspotPreview bottom');
    }

    if (xxxx < 254 && bottomFromShirota-n.x> 150 && xxx>150) {

    preview.css('left', ''+left+'px')
    preview.css('top', ''+bottom+'px')
    uzb360preview.removeClass();
    uzb360preview.addClass('hotspotPreview top');
    }


    hotspottext.text(maphotspotname);
    hotspoticon.attr("src","/storage/cat_icons/"+maphotspoticon+"");
    hotspotimg.attr("src","/storage/panoramas/unpacked/"+maphotspotimg+"/thumb.jpg");






    });

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
    	if(willAnimatePanTo(map, latLng, currentZoom - 1)) {
    		return currentZoom - 1
    	} else if(willAnimatePanTo(map, latLng, currentZoom - 2)) {
    		return currentZoom - 2
    	} else {
    		return currentZoom - 3
    	}
    }
    function smoothlyAnimatePanToWorkarround(map, destLatLng, optionalAnimationEndCallback) {
  	var initialZoom = map.getZoom(), listener

  	function zoomIn() {
  		if(map.getZoom() < initialZoom) {
  			map.setZoom(Math.min(map.getZoom() + 3, initialZoom))
  		} else {
  			google.maps.event.removeListener(listener)

  			map.setOptions({draggable: true, zoomControl: true, scrollwheel: true, disableDoubleClickZoom: false})

  			if(!!optionalAnimationEndCallback) {
  				optionalAnimationEndCallback()
  			}
  		}
  	}

  	function zoomOut() {
  		if(willAnimatePanTo(map, destLatLng)) {
  			google.maps.event.removeListener(listener)
  			listener = google.maps.event.addListener(map, 'idle', zoomIn)
  			map.panTo(destLatLng)
  		} else {
  			map.setZoom(getOptimalZoomOut(destLatLng, map.getZoom()))
  		}
  	}

  	map.setOptions({draggable: false, zoomControl: false, scrollwheel: false, disableDoubleClickZoom: true})
  	map.setZoom(getOptimalZoomOut(destLatLng, initialZoom))
  	listener = google.maps.event.addListener(map, 'idle', zoomOut)
  }

  function smoothlyAnimatePanTo(map, destLatLng) {
  	if(willAnimatePanTo(map, destLatLng)) {
  		map.panTo(destLatLng)
  	} else {
  		smoothlyAnimatePanToWorkarround(map, destLatLng)
  	}
  }




    $('.featuredloctionbox').mouseover(function(){
      var latmark = $(this).data('lat');
      var lngmark = $(this).data('lng');
smoothlyAnimatePanTo(map, new google.maps.LatLng(latmark,lngmark));

    });
         google.maps.event.addDomListener(marker, 'click', function() {


   loadpano('uzbekistan:'+value.id, '1', value.slug);
   setTimeout(function(){
     $('.icon-ic_close').trigger('click')
   }, 100);

});
   });



}
    </script>

    <script>
        embedpano({target: "pano", id: "pano1", xml: "/skykrpano/0/{{ $location->id }}", passQueryParameters:true, html5:"only+webgl", onready: krpano_onready_callback});
    </script>
@endsection
