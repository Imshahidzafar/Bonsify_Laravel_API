<div class="header_search_area">
    <!-- SEARCH HEADER -->
    <div class="search_ar_go">
    <div class="search-classified-text text-center generic-white">
      <h1>Find Used Cars in Pakistan</h1>
      <p>With thousands of cars, we have just the right one for you</p>
    </div>
    </div>
    
    <!-- SEARCH BODY -->
    <div class="search-classified" tabindex="0">
      <div id="used-cars">
        <ul class="list-unstyled search-front clearfix">
          <li class="home-autocomplete-field">
            <input
              data-autocomplete-class="home-autocomplete"
              data-pw-source="car"
              id="home-query"
              name="home-query"
              placeholder="Car Make or Model"
              tabindex="2"
              type="text"
              value=""
              class="ui-autocomplete-input"
              autocomplete="off"
            /><span
              role="status"
              aria-live="polite"
              class="ui-helper-hidden-accessible"
            ></span>
            <input type="hidden" name="UsedManID" id="UsedManID" />
            <input
              type="hidden"
              name="UsedModelID"
              id="UsedModelID"
              onchange="updateVersion('car', $('#UsedModelID').val(), $('#UsedManID').val(), $('#UsedVersionID')); "
            />
            <input
              type="hidden"
              data-slug=""
              name="UsedVersionGroupID"
              id="UsedVersionGroupID"
            />
            <input
              type="hidden"
              data-slug=""
              name="UsedVersionIDAutoFill"
              id="UsedVersionIDAutoFill"
            />
          </li>
          <li class="home-chzn">
            <div
              id="UsedCity_chzn"
              class="chzn-container chzn-container-single"
              style="width: 220px"
            >
              <a href="javascript:void(0)" class="chzn-single" tabindex="1"
                ><span>All Cities</span>
                <div><b></b></div
              ></a>
              <div
                class="chzn-drop"
                style="left: -9000px; width: 218px; top: 50px"
              >
                <div class="chzn-search">
                  <input
                    type="text"
                    autocomplete="off"
                    tabindex="-1"
                    style="width: 176px"
                  />
                </div>
              </div>
            </div>
          </li>
          <li class="range-widget">
            <div id="pr-range-filter" tabindex="3" class="pos-rel">
              <span class="pr-text">Price Range</span>
              <i class="fa fa-sort-down pull-right"></i>
              <div class="pr-range" style="display: none">
                <div class="pr-range-container">
                  <div class="pr-input-container clearfix">
                    <div class="pr-input">
                      <span
                        class="twitter-typeahead"
                        style="position: relative; display: inline-block"
                        ><input
                          id="pr_from"
                          name="pr_from"
                          placeholder="Min"
                          tabindex="4"
                          type="text"
                          value=""
                          class="tt-input"
                          autocomplete="off"
                          spellcheck="false"
                          dir="auto"
                          style="position: relative; vertical-align: top" />
                        <pre
                          aria-hidden="true"
                          style="
                            position: absolute;
                            visibility: hidden;
                            white-space: pre;
                            font-family: Lato, sans-serif;
                            font-size: 14px;
                            font-style: normal;
                            font-variant: normal;
                            font-weight: 400;
                            word-spacing: 0px;
                            letter-spacing: 0px;
                            text-indent: 0px;
                            text-rendering: auto;
                            text-transform: none;
                          "
                        ></pre>
                        <div
                          class="tt-menu"
                          style="
                            position: absolute;
                            top: 100%;
                            left: 0px;
                            z-index: 100;
                            display: none;
                          "
                        >
                          <div
                            class="tt-dataset tt-dataset-0"
                          ></div></div></span
                      >lacs
                    </div>
                    <div class="pr-input">
                      <span
                        class="twitter-typeahead"
                        style="position: relative; display: inline-block"
                        ><input
                          id="pr_to"
                          name="pr_to"
                          placeholder="Max"
                          tabindex="5"
                          type="text"
                          value=""
                          class="tt-input"
                          autocomplete="off"
                          spellcheck="false"
                          dir="auto"
                          style="position: relative; vertical-align: top" />
                        <pre
                          aria-hidden="true"
                          style="
                            position: absolute;
                            visibility: hidden;
                            white-space: pre;
                            font-family: Lato, sans-serif;
                            font-size: 14px;
                            font-style: normal;
                            font-variant: normal;
                            font-weight: 400;
                            word-spacing: 0px;
                            letter-spacing: 0px;
                            text-indent: 0px;
                            text-rendering: auto;
                            text-transform: none;
                          "
                        ></pre>
                        <div
                          class="tt-menu"
                          style="
                            position: absolute;
                            top: 100%;
                            left: 0px;
                            z-index: 100;
                            display: none;
                          "
                        >
                          <div
                            class="tt-dataset tt-dataset-1"
                          ></div></div></span
                      >lacs
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </li>
    
          <li>
            <div id="search-row">
              <button
                type="submit"
                class="btn btn-success btn-lg btn-block"
                onclick="return onCarSubmit();"
                id="home-search-btn"
                tabindex="6"
              >
                <i class="fa fa-search"></i>
              </button>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <!-- SEARCH BUTTON -->
    <div class="btn-classified-container text-center">
      <a
        href="https://www.pakwheels.com/used-cars/search/-/"
        class="btn btn-sm btn-link-outline btn-classified"
        style="z-index: 5; position: relative"
        >Advanced Filter <i class="fa fa-angle-right"></i
      ></a>
    </div>
</div>