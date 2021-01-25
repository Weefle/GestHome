<div id="menu">
    <ul>

        <li class="<?php echo ( $pageAffiche == 'admin_groupes' ? 'selected' : '' ); ?>">
            <a href="index.php?page=admin_groupes">
                <span class="textCenter fontSize20 fa fa-cog"></span>Administration des groupes
            </a>
        </li>
        <li class="<?php echo ( $pageAffiche == 'admin_sondes' ? 'selected' : '' ); ?>">
            <a href="index.php?page=admin_sondes">
                <span class="textCenter fontSize20 fa fa-cog"></span>Administration des sondes
            </a>
        </li>
        <li class="<?php echo ( $pageAffiche == 'admin_utilisateurs' ? 'selected' : '' ); ?>">
            <a href="index.php?page=admin_utilisateurs">
                <span class="textCenter fontSize20 fa fa-cog"></span>Administration des utilisateurs
            </a>
        </li>
   </ul>
</div>
