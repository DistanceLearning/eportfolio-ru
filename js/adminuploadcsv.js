/**
 * Support file for the uploadcsv admin page in Mahara
 * @source: http://gitorious.org/mahara/mahara
 *
 * @licstart
 * Copyright (C) 2010  Catalyst IT Ltd
 *
 * The JavaScript code in this page is free software: you can
 * redistribute it and/or modify it under the terms of the GNU
 * General Public License (GNU GPL) as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option)
 * any later version.  The code is distributed WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU GPL for more details.
 *
 * As additional permission under GNU GPL version 3 section 7, you
 * may distribute non-source (e.g., minimized or compacted) forms of
 * that code without the copy of the GNU GPL normally required by
 * section 4, provided you include this license notice and a URL
 * through which recipients can access the Corresponding Source.
 * @licend
 */

function change_quota(i) {
    var quota = document.getElementById('uploadcsv_quota');
    var quotaUnits = document.getElementById('uploadcsv_quota_units');
    var params = {};
    params.instid = i.value;
    if (quotaUnits == null) {
        params.disabled = true;
    }
    sendjsonrequest('quota.json.php', params, 'POST', function(data) {
        if (quotaUnits == null) {
            quota.value = data.data;
        }
        else {
            quota.value = data.data.number;
            quotaUnits.value = data.data.units;
        }
    });
}

addLoadEvent(function() {
    select = document.getElementById('uploadcsv_authinstance');
    if (select != null) {
        connect(select, 'onchange', partial(change_quota, select));
    }
    else {
        select = document.getElementsByName('authinstance')[0];
    }
    change_quota(select);
});
