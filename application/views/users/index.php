<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<h1><i class='fa fa-user'></i> Users</h1>
    
<input type='hidden' id='token' value='<?=$this->security->get_csrf_hash()?>'>

<p class='lead'>Below is a list of all users. You can login as each user in order to troubleshoot functionality.</p>

<?php
if (empty($companyUsers))
{
    echo $this->alerts->error("No users found, this can't be right =/");
}
else
{
echo <<< EOS
    <table class='table table-hover table-bordered' id='userTbl'>
        <thead>
            <tr>
                <th>Name</th>
                <th>User ID</th>
                <th>Position</th>
                <th>Status</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
EOS;

    foreach ($companyUsers as $r)
    {
        $name = $positionName = $statusDisplay = null;

        $deleted = false;

        try
        {
            $deleted = $this->users->isDeleted($r->userid);

            if ($deleted == true) continue;

            $name = $this->users->getName($r->userid);

            $userCompanyPosition = $this->users->getUserCompanyPosition($r->userid);

            if (empty($userCompanyPosition)) $positionName = "<small><span class='text-muted'>(Not assigned)</span></small>";
            else $positionName = $this->users->getPositionName($userCompanyPosition);

            $status = $this->users->getStatus($r->userid);

            $statusDisplay = $this->functions->codeDisplay(7, $status);
        }
        catch (Exception $e)
        {
            $this->functions->sendStackTrace($e);
        }

        echo "<tr>" . PHP_EOL;

        echo "\t<td>{$name}</td>" . PHP_EOL;
        echo "\t<td>{$r->userid}</td>" . PHP_EOL;
        echo "\t<td>{$positionName}</td>" . PHP_EOL;
        echo "\t<td>{$statusDisplay}</td>" . PHP_EOL;
        echo "\t<td><button type='button' class='btn btn-primary btn-sm pull-right' onclick=\"users.loginas(this, {$r->userid})\"><i class='fa fa-sign-in'></i> Login As</button></td>" . PHP_EOL;
        
        echo "</tr>" . PHP_EOL;
    }

echo "</tbody>" . PHP_EOL;
echo "</table>" . PHP_EOL;
}
?>
