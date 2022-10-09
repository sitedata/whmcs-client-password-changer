{literal}<style>ul.b {list-style-type: square;}.mb-10{margin-bottom:10px;}</style>{/literal}

<div class="row">
    <div class="col-md-10 col-md-offset-1 mb-10 col-lg-8 col-lg-offset-2">

        {if $updateAvailable}
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        An update is available for this script. Please download it from <a href="https://github.com/leemahoney3/whmcs-client-password-changer" target="_blank">here</a>.
                    </div>
                </div>
            </div>
        {/if}

        {if PageHelper::getAttribute('error') == 'nouser'}
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        No such user exists with that ID.
                    </div>
                </div>
            </div>
        {/if}

        {if PageHelper::getAttribute('success') == 'changed'}
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        The users password has been updated successfully.
                    </div>
                </div>
            </div>
        {/if}

        <div class="row">
            <div class="col-md-8 col-lg-10">
                Please choose a user from the list below.
            </div>
            <div class="col-md-4 col-lg-2">
                <form action="" method="post">
                    <div class="input-group">
                        <input type="text" placeholder="Search by users name..." class="form-control" name="search" class="pull-right">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Associated Clients</th>
                            <th>Last Login</th>
                            <th>Last Login IP</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {if !count($users)}
                            <tr>
                                <td colspan="7" class="text-center">No users found</td>
                            </tr>
                        {/if}
                        {foreach $users as $user}
                            <tr>
                                <td>{$user->id}</td>
                                <td>{$user->first_name} {$user->last_name}</td>
                                <td>{$user->email}</td>
                                <td>
                                    {foreach $user->clients as $client}
                                        <li><a target='_blank' href='clientssummary.php?userid={$client->id}'>{$client->firstname} {$client->lastname}</a></li>
                                    {/foreach} 
                                </td>
                                <td>{$user->last_login}</td>
                                <td>{$user->last_ip}</td>
                                <td class='text-center'><a href='{$moduleLink}&page=change&id={$user->id}' class='btn btn-primary'>Change Password</a></td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        {$paginationLinks}
    </div>
</div>