<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-ca">
<head>
   <?php $this->RenderAsset('Head'); ?>
   <!-- Robots should not see the dashboard, but tell them not to index it just in case. -->
   <meta name="robots" content="noindex,nofollow" />
</head>
<body id="<?php echo $BodyIdentifier; ?>" class="<?php echo $this->CssClass; ?>">
   <div id="Frame">
      <div id="Head">
			<h1><?php echo Anchor(C('Garden.Title').' '.Wrap(T('Visit Site')), '/'); ?></h1>
         <div class="User">
            <?php
			      $Session = Gdn::Session();
					if ($Session->IsValid()) {
						$this->FireEvent('BeforeUserOptionsMenu');

						$Name = $Session->User->Name;
						$CountNotifications = $Session->User->CountNotifications;
						if (is_numeric($CountNotifications) && $CountNotifications > 0)
							$Name .= Wrap($CountNotifications);

						echo Anchor($Name, UserUrl($Session->User), 'Profile');
						echo Anchor(T('Sign Out'), SignOutUrl(), 'Leave');
					}
				?>
         </div>
      </div>
      <div id="Body">
          <div id="Panel">
          <?php
          $dropdown = new DropDownMenuModule($this, 'my-dropdown', 'Trigger Name', '', 'dropdown-menu-right');
          $dropdown->setTrigger('A New Name', 'button', 'btn-default', 'caret');
          $dropdown->addLink(array('text' => 'Link 1', 'url' => '#')); // Automatically creates key: item1
          $dropdown->addDivider(''); // Automatically creates key: item2
          $dropdown->addHeader('Header 1'); // Automatically creates key: item3
          $dropdown->addLink(array('text' => 'Link 2', 'url' => '#', 'key' => 'link2', 'class' => 'bg-danger')); // Creates item with key: link2
          $dropdown->addLinks(array(
             array('text' => 'Link 3', 'url' => '#'), // Automatically creates key: item4
             array('text' => 'Link 4', 'url' => '#')
                  ));
          $dropdown->addGroup(array('key' => 'group1')); // Creates group with no header
          $dropdown->addGroup(array('text' => 'Group 2', 'key' => 'group2')); // Creates group with header: 'Group 2'
          $dropdown->addLink(array('text' => 'Link 5', 'url' => '#', 'sort'=>array('before', 'link2'), 'badge' => 4)); // Inserts before Link 2
          $dropdown->addLinks(array(
             array('text' => 'Link 6', 'url' => '#'),
             array('text' => 'Link 7', 'url' => '#')
                  ));
          $dropdown->addLink(array('text' => 'Link 8', 'url' => '#', 'disabled'=>true, 'key' => 'group2.link8', 'icon' => 'icon-flame')); // Adds to Group 2
          $dropdown->addLink(array('text' => 'Link 9', 'url' => '#', 'disabled'=>true, 'key' => 'group1.link9')); // Adds to Group 1
          $dropdown->addLink(array('text' => 'Link 10', 'url' => '#', 'key' => 'group1.link10')); // Adds to Group 1
          echo $dropdown->toString();

          $this->RenderAsset('Panel');
          ?>
          </div>
          <div id="Content"><?php $this->RenderAsset('Content'); ?></div>


      <div id="Foot">
			<?php
				$this->RenderAsset('Foot');
				echo '<div class="Version">Version ', APPLICATION_VERSION, '</div>';
				echo Wrap(Anchor(Img('/applications/dashboard/design/images/logo_footer.png', array('alt' => 'Vanilla Forums')), C('Garden.VanillaUrl')), 'div');
			?>
		</div>
   </div>
	<?php $this->FireEvent('AfterBody'); ?>
</body>
</html>
