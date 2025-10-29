(function (root, factory) {
    if (typeof module === "object" && module.exports) {
        // Node.js
        module.exports = factory();
    } else {
        // Browser
        root.UTMLatLng = factory();
    }
})(typeof self !== "undefined" ? self : this, function () {
    var datumName = "WGS 84";
    var a;
    var eccSquared;
    var status = false;

    function UTMLatLng(datumNameIn) {
        if (datumNameIn !== undefined) {
            datumName = datumNameIn;
        }
        this.setEllipsoid(datumName);
    }

    UTMLatLng.prototype.setEllipsoid = function (name) {
        var ellipsoid = [
            { id: "Airy", ellipsoidName: "Airy", EquatorialRadius: 6377563, eccentricitySquared: 0.00667054 },
            {
                id: "Australian National",
                ellipsoidName: "Australian National",
                EquatorialRadius: 6378160,
                eccentricitySquared: 0.006694542,
            },
            {
                id: "Bessel 1841",
                ellipsoidName: "Bessel 1841",
                EquatorialRadius: 6377397,
                eccentricitySquared: 0.006674372,
            },
            {
                id: "Bessel 1841 Nambia",
                ellipsoidName: "Bessel 1841 Nambia",
                EquatorialRadius: 6377484,
                eccentricitySquared: 0.006674372,
            },
            {
                id: "Clarke 1866",
                ellipsoidName: "Clarke 1866",
                EquatorialRadius: 6378206,
                eccentricitySquared: 0.006768658,
            },
            {
                id: "Clarke 1880",
                ellipsoidName: "Clarke 1880",
                EquatorialRadius: 6378249,
                eccentricitySquared: 0.006803511,
            },
            { id: "Everest", ellipsoidName: "Everest", EquatorialRadius: 6377276, eccentricitySquared: 0.006637847 },
            {
                id: "Fischer 1960 Mercury",
                ellipsoidName: "Fischer 1960 Mercury",
                EquatorialRadius: 6378166,
                eccentricitySquared: 0.006693422,
            },
            {
                id: "Fischer 1968",
                ellipsoidName: "Fischer 1968",
                EquatorialRadius: 6378150,
                eccentricitySquared: 0.006693422,
            },
            { id: "GRS 1967", ellipsoidName: "GRS 1967", EquatorialRadius: 6378160, eccentricitySquared: 0.006694605 },
            { id: "GRS 1980", ellipsoidName: "GRS 1980", EquatorialRadius: 6378137, eccentricitySquared: 0.00669438 },
            {
                id: "Helmert 1906",
                ellipsoidName: "Helmert 1906",
                EquatorialRadius: 6378200,
                eccentricitySquared: 0.006693422,
            },
            { id: "Hough", ellipsoidName: "Hough", EquatorialRadius: 6378270, eccentricitySquared: 0.00672267 },
            {
                id: "International",
                ellipsoidName: "International",
                EquatorialRadius: 6378388,
                eccentricitySquared: 0.00672267,
            },
            {
                id: "Krassovsky",
                ellipsoidName: "Krassovsky",
                EquatorialRadius: 6378245,
                eccentricitySquared: 0.006693422,
            },
            {
                id: "Modified Airy",
                ellipsoidName: "Modified Airy",
                EquatorialRadius: 6377340,
                eccentricitySquared: 0.00667054,
            },
            {
                id: "Modified Everest",
                ellipsoidName: "Modified Everest",
                EquatorialRadius: 6377304,
                eccentricitySquared: 0.006637847,
            },
            {
                id: "Modified Fischer 1960",
                ellipsoidName: "Modified Fischer 1960",
                EquatorialRadius: 6378155,
                eccentricitySquared: 0.006693422,
            },
            {
                id: "South American 1969",
                ellipsoidName: "South American 1969",
                EquatorialRadius: 6378160,
                eccentricitySquared: 0.006694542,
            },
            { id: "WGS 60", ellipsoidName: "WGS 60", EquatorialRadius: 6378165, eccentricitySquared: 0.006693422 },
            { id: "WGS 66", ellipsoidName: "WGS 66", EquatorialRadius: 6378145, eccentricitySquared: 0.006694542 },
            { id: "WGS 72", ellipsoidName: "WGS 72", EquatorialRadius: 6378135, eccentricitySquared: 0.006694318 },
            { id: "ED50", ellipsoidName: "ED50", EquatorialRadius: 6378388, eccentricitySquared: 0.00672267 },
            { id: "WGS 84", ellipsoidName: "WGS 84", EquatorialRadius: 6378137, eccentricitySquared: 0.00669438 },
            { id: "EUREF89", ellipsoidName: "EUREF89", EquatorialRadius: 6378137, eccentricitySquared: 0.00669438 },
            { id: "ETRS89", ellipsoidName: "ETRS89", EquatorialRadius: 6378137, eccentricitySquared: 0.00669438 },
        ];

        for (var i = 0; i < ellipsoid.length; i++) {
            if (ellipsoid[i].ellipsoidName === name) {
                a = ellipsoid[i].EquatorialRadius;
                eccSquared = ellipsoid[i].eccentricitySquared;
                this.status = false;
                return;
            }
        }
        this.status = true;
    };

    UTMLatLng.prototype.convertLatLngToUtm = function (latitude, longitude, precision) {
        if (this.status) {
            return "No ellipsoid data associated with unknown datum: " + datumName;
        }
        if (!Number.isInteger(precision)) {
            return "Precision is not an integer number.";
        }

        latitude = parseFloat(latitude);
        longitude = parseFloat(longitude);

        var LongTemp = longitude;
        var LatRad = this.toRadians(latitude);
        var LongRad = this.toRadians(LongTemp);
        var ZoneNumber;

        // Special zones for Norway and Svalbard
        if (LongTemp >= 8 && LongTemp <= 13 && latitude > 54.5 && latitude < 58) {
            ZoneNumber = 32;
        } else if (latitude >= 56.0 && latitude < 64.0 && LongTemp >= 3.0 && LongTemp < 12.0) {
            ZoneNumber = 32;
        } else {
            ZoneNumber = Math.floor((LongTemp + 180) / 6) + 1;

            if (latitude >= 72.0 && latitude < 84.0) {
                if (LongTemp >= 0.0 && LongTemp < 9.0) {
                    ZoneNumber = 31;
                } else if (LongTemp >= 9.0 && LongTemp < 21.0) {
                    ZoneNumber = 33;
                } else if (LongTemp >= 21.0 && LongTemp < 33.0) {
                    ZoneNumber = 35;
                } else if (LongTemp >= 33.0 && LongTemp < 42.0) {
                    ZoneNumber = 37;
                }
            }
        }

        var LongOrigin = (ZoneNumber - 1) * 6 - 180 + 3;
        var LongOriginRad = this.toRadians(LongOrigin);

        var eccPrimeSquared = eccSquared / (1 - eccSquared);

        var N = a / Math.sqrt(1 - eccSquared * Math.sin(LatRad) * Math.sin(LatRad));
        var T = Math.tan(LatRad) * Math.tan(LatRad);
        var C = eccPrimeSquared * Math.cos(LatRad) * Math.cos(LatRad);
        var A = Math.cos(LatRad) * (LongRad - LongOriginRad);

        var M =
            a *
            ((1 -
                eccSquared / 4 -
                (3 * eccSquared * eccSquared) / 64 -
                (5 * eccSquared * eccSquared * eccSquared) / 256) *
                LatRad -
                ((3 * eccSquared) / 8 +
                    (3 * eccSquared * eccSquared) / 32 +
                    (45 * eccSquared * eccSquared * eccSquared) / 1024) *
                    Math.sin(2 * LatRad) +
                ((15 * eccSquared * eccSquared) / 256 + (45 * eccSquared * eccSquared * eccSquared) / 1024) *
                    Math.sin(4 * LatRad) -
                ((35 * eccSquared * eccSquared * eccSquared) / 3072) * Math.sin(6 * LatRad));

        var UTMEasting =
            0.9996 *
                N *
                (A +
                    ((1 - T + C) * A * A * A) / 6 +
                    ((5 - 18 * T + T * T + 72 * C - 58 * eccPrimeSquared) * A * A * A * A * A) / 120) +
            500000.0;

        var UTMNorthing =
            0.9996 *
            (M +
                N *
                    Math.tan(LatRad) *
                    ((A * A) / 2 +
                        ((5 - T + 9 * C + 4 * C * C) * A * A * A * A) / 24 +
                        ((61 - 58 * T + T * T + 600 * C - 330 * eccPrimeSquared) * A * A * A * A * A * A) / 720));

        if (latitude < 0) {
            UTMNorthing += 10000000.0;
        }

        return {
            Easting: this.toFixedNumber(UTMEasting, precision),
            Northing: this.toFixedNumber(UTMNorthing, precision),
            ZoneNumber: ZoneNumber,
            ZoneLetter: this.getLetterDesignator(latitude),
        };
    };

    UTMLatLng.prototype.convertUtmToLatLng = function (easting, northing, zoneNumber, zoneLetter) {
        if (this.status) {
            return "No ellipsoid data associated with unknown datum: " + datumName;
        }

        easting = parseFloat(easting);
        northing = parseFloat(northing);
        zoneNumber = parseInt(zoneNumber);

        var e1 = (1 - Math.sqrt(1 - eccSquared)) / (1 + Math.sqrt(1 - eccSquared));
        var x = easting - 500000.0;
        var y = northing;

        var NorthernHemisphere;
        if (["N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"].indexOf(zoneLetter) > -1) {
            NorthernHemisphere = 1;
        } else {
            NorthernHemisphere = 0;
            y -= 10000000.0;
        }

        var LongOrigin = (zoneNumber - 1) * 6 - 180 + 3;

        var eccPrimeSquared = eccSquared / (1 - eccSquared);

        var M = y / 0.9996;
        var mu =
            M /
            (a *
                (1 -
                    eccSquared / 4 -
                    (3 * eccSquared * eccSquared) / 64 -
                    (5 * eccSquared * eccSquared * eccSquared) / 256));

        var phi1Rad =
            mu +
            ((3 * e1) / 2 - (27 * e1 * e1 * e1) / 32) * Math.sin(2 * mu) +
            ((21 * e1 * e1) / 16 - (55 * e1 * e1 * e1 * e1) / 32) * Math.sin(4 * mu) +
            ((151 * e1 * e1 * e1) / 96) * Math.sin(6 * mu);

        var N1 = a / Math.sqrt(1 - eccSquared * Math.sin(phi1Rad) * Math.sin(phi1Rad));
        var T1 = Math.tan(phi1Rad) * Math.tan(phi1Rad);
        var C1 = eccPrimeSquared * Math.cos(phi1Rad) * Math.cos(phi1Rad);
        var R1 = (a * (1 - eccSquared)) / Math.pow(1 - eccSquared * Math.sin(phi1Rad) * Math.sin(phi1Rad), 1.5);
        var D = x / (N1 * 0.9996);

        var lat =
            phi1Rad -
            ((N1 * Math.tan(phi1Rad)) / R1) *
                ((D * D) / 2 -
                    ((5 + 3 * T1 + 10 * C1 - 4 * C1 * C1 - 9 * eccPrimeSquared) * D * D * D * D) / 24 +
                    ((61 + 90 * T1 + 298 * C1 + 45 * T1 * T1 - 252 * eccPrimeSquared - 3 * C1 * C1) *
                        D *
                        D *
                        D *
                        D *
                        D *
                        D) /
                        720);
        lat = this.toDegrees(lat);

        var lng =
            (D -
                ((1 + 2 * T1 + C1) * D * D * D) / 6 +
                ((5 - 2 * C1 + 28 * T1 - 3 * C1 * C1 + 8 * eccPrimeSquared + 24 * T1 * T1) * D * D * D * D * D) / 120) /
            Math.cos(phi1Rad);
        lng = LongOrigin + this.toDegrees(lng);

        return {
            lat: lat,
            lng: lng,
        };
    };

    UTMLatLng.prototype.getLetterDesignator = function (lat) {
        if (-80 <= lat && lat <= 84) {
            var letters = "CDEFGHJKLMNPQRSTUVWXX";
            var index = Math.floor((lat + 80) / 8);
            return letters.charAt(index);
        }
        return "Z";
    };

    UTMLatLng.prototype.toRadians = function (deg) {
        return (deg * Math.PI) / 180;
    };

    UTMLatLng.prototype.toDegrees = function (rad) {
        return (rad * 180) / Math.PI;
    };

    UTMLatLng.prototype.toFixedNumber = function (num, digits) {
        return parseFloat(num.toFixed(digits));
    };

    return UTMLatLng;
});
