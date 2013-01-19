Album - <?=$album->title?>
<table class="lentele" cellpadding="0" cellspacing="0">
    <tr>
        <th>Name</th>
        <th>Version</th>
        <th>Additional info</th>
    </tr>
    <tr>
        <td>Album version</td>
        <td><?=$album_version?></td>
        <td>General album version</td>
    </tr>
    <tr>
        <td>Top rated version</td>
        <td><?=$top_rated?></td>
        <td>Expired on album image voting</td>
    </tr>
    <tr>
        <td>Last commented</td>
        <td><?=$last_commented?></td>
        <td>Expires on image comment</td>
    </tr>
    <tr>
        <td>Last rated</td>
        <td><?=$last_rated?></td>
        <td>Expires on image rate</td>
    </tr>           
</table>