HCService.FormManager.Objects.GoogleMapsField = function () {
    this.inheritFrom = HCService.FormManager.Objects.BasicField;
    this.inheritFrom();

    /**
     * Field identification name
     * @type String
     */
    this.fieldName = 'google-map';

    var globalMap;

    /**
     * Handling properties of the input field
     *
     * @method handleProperties
     */
    this.handleProperties = function () {
        this.innerHTML = $('<div></div>');
        this.inputField = $('<div class="pac-card" id="pac-card">\n' +
            '      <div>\n' +
            '        <div id="title">\n' +
            '          Autocomplete search\n' +
            '        </div>\n' +
            '        <div id="type-selector" class="pac-controls">\n' +
            '          <input type="radio" name="type" id="changetype-all" checked="checked">\n' +
            '          <label for="changetype-all">All</label>\n' +
            '\n' +
            '          <input type="radio" name="type" id="changetype-establishment">\n' +
            '          <label for="changetype-establishment">Establishments</label>\n' +
            '\n' +
            '          <input type="radio" name="type" id="changetype-address">\n' +
            '          <label for="changetype-address">Addresses</label>\n' +
            '\n' +
            '          <input type="radio" name="type" id="changetype-geocode">\n' +
            '          <label for="changetype-geocode">Geocodes</label>\n' +
            '        </div>\n' +
            '        <div id="strict-bounds-selector" class="pac-controls">\n' +
            '          <input type="checkbox" id="use-strict-bounds" value="">\n' +
            '          <label for="use-strict-bounds">Strict Bounds</label>\n' +
            '        </div>\n' +
            '      </div>\n' +
            '      <div id="pac-container">\n' +
            '        <input id="pac-input" type="text"\n' +
            '            placeholder="Enter a location">\n' +
            '      </div>\n' +
            '    </div>\n' +
            '    <div id="google-map" style="height: 500px; width: 568px;"></div>\n' +
            '    <div id="infowindow-content">\n' +
            '      <img src="" width="16" height="16" id="place-icon">\n' +
            '      <span id="place-name"  class="title"></span><br>\n' +
            '      <span id="place-address"></span>\n' +
            '    </div>');

        this.innerHTML.append(this.inputField);
        this.checkForMultiLanguage();
        this.innerHTML.append(this.getAnnotation());
    };

    this.updateWhenOnStageLocal = function () {
        this.initializeMap();
    };

    var autoComplete;

    this.getContentData = function () {

        if (autoComplete)
            return JSON.stringify(autoComplete.getPlace());

        return '';
    };


    this.setContentData = function (value) {

        if (value) {
            value = JSON.parse(value);

            var $input = $(input);
            $input.val(value.formatted_address);
            autoComplete.set("place", value);
        }

        this.triggerContentChange();
    };

    var input;


    this.initializeMap = function () {

        var self = this;

        var options = {
            center: {
                lat: 54.8985207,
                lng: 23.90359650000005
            },
            zoom: 13
        };

        var map = new google.maps.Map(document.getElementById('google-map'), options);

        globalMap = map;

        var card = document.getElementById('pac-card');
        var types = document.getElementById('type-selector');
        var strictBounds = document.getElementById('strict-bounds-selector');

        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);
        input = document.getElementById('pac-input');

        autoComplete = new google.maps.places.Autocomplete(input);

        // Bind the map's bounds (viewport) property to the autoComplete object,
        // so that the autoComplete requests use the current map bounds for the
        // bounds option in the request.
        autoComplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var infowindowContent = document.getElementById('infowindow-content');
        infowindow.setContent(infowindowContent);
        var marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29)
        });

        autoComplete.addListener('place_changed', function () {
            infowindow.close();
            marker.setVisible(false);
            var place = autoComplete.getPlace();
            if (!place.geometry) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                window.alert("No details available for input: '" + place.name + "'");
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
            }
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            // SET FIELD DATA
            self.triggerContentChange();

            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }

            infowindowContent.children['place-icon'].src = place.icon;
            infowindowContent.children['place-name'].textContent = place.name;
            infowindowContent.children['place-address'].textContent = address;
            infowindow.open(map, marker);
        });

        // Sets a listener on a radio button to change the filter type on Places
        // Autocomplete.
        function setupClickListener(id, types) {
            var radioButton = document.getElementById(id);
            radioButton.addEventListener('click', function () {
                autoComplete.setTypes(types);
            });
        }

        setupClickListener('changetype-all', []);
        setupClickListener('changetype-address', ['address']);
        setupClickListener('changetype-establishment', ['establishment']);
        setupClickListener('changetype-geocode', ['geocode']);

        document.getElementById('use-strict-bounds')
            .addEventListener('click', function () {
                autoComplete.setOptions({strictBounds: this.checked});
            });

        google.maps.event.trigger(map, 'resize');

    }

};