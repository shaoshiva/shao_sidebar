<? $container_id = uniqid(); ?>
<style type="text/css">
.sidebar-blocs {
}
.sidebar-blocs .blocs {
	margin: 1em 0;
	border: 1px solid #ccc;
	border-radius: 6px;
}
.sidebar-blocs .blocs ul {
}
.sidebar-blocs div > .actions {
    float: right;
    margin-top: -5px;
}
.sidebar-blocs .blocs .actions button {
    width: 20px;
    height: 21px;
    top: 4px;
    right: 2px;
    opacity: 0.5;
}
.sidebar-blocs .blocs .actions button:hover {
    opacity: 1;
}
.sidebar-blocs .blocs .actions button .ui-icon {
    margin-left: -5px;
}
.sidebar-blocs .blocs .actions button .ui-button-text {
    display: none;
}
.sidebar-blocs .blocs h2 em {
    font-style: italic;
}
.sidebar-blocs .blocs h2 .actions {
    position: absolute;
    top: 0;
    right: 0;
	height: 27px;
}
.sidebar-blocs .bloc {
    overflow: visible !important;
	padding-top: 13px !important;
    padding-bottom: 13px !important;
}
.sidebar-blocs .sliding .bloc {
    overflow: hidden !important;
}
.sidebar-blocs .bloc .mceEditor {
	display: block !important;
}
.sidebar-blocs .options {
    margin: 1em 0 0 0 !important;
}
.sidebar-blocs ul {
    padding: 2em;
	min-height: 10em;
	list-style-type: none;
}
.sidebar-blocs .bloc_title input {
	width: 100%;
    box-sizing: border-box;
}
.sidebar-published {
    float: left;
    width: 35em;
}
.sidebar-unpublished {
    float: left;
    width: 35em;
    margin-left: 3em;
}
.sidebar-unpublished .blocs {
    position: relative;
    border: 1px dashed #cccccc;
    min-height: 10em;
	opacity: 0.7;
}
.sidebar-unpublished .blocs > em {
    position: absolute;
	height: 18px;
	width: 100%;
	top: 50%;
    left: 0;
	margin-top: -9px;
    text-align: center;
}
</style>
<script type="text/javascript">
$(function() {

	var $container = $('#sidebar-blocs-<?= $container_id ?>');

	// Create skeleton
    var $skeleton = $container.find('.blocs .bloc').first().clone();

	// Add new bloc
	$('.add-new-bloc').bind('click', function add_bloc(e) {
		e.preventDefault();
		e.stopPropagation();
		e.stopImmediatePropagation();

		// Create new bloc
        var $blocs = $container.find('.blocs');
		var $new = $skeleton.clone();

        var n = $blocs.find('.bloc').length;

        // Reset inputs, increment keys and ids
        $new.find('[name^="bloc["]').each(function() {
			$(this).attr('name', $(this).attr('name').replace(/\[[0-9]+\]/g, "["+n+"]")).val('');
        });
		var editor_id = $new.find('.wys textarea').attr('id')+n;
        $new.find('.wys textarea').attr('id', editor_id).val('').show();
        $new.find('.mceEditor').remove();

		// Add to dom
        $container.find('.sidebar-published ul').append($('<li></li>').append($new));
		$new.before('<h2><em><?= _('Bloc') ?></em></h2>');

		// Reload wysiwyg editor
        reload_wys_editor(editor_id);

		// Reload UI
        $container.find('.blocs').wijaccordion('destroy');
        $container.find('.blocs ul').sortable('destroy');
        init_blocs();
        var default_accordion = $container.find('.sidebar-published .blocs .wijmo-wijaccordion-content-active').first().closest('li').index();
        $container.find('.sidebar-published .blocs').wijaccordion("activate", default_accordion);
	});

	// Load UI
    init_blocs();

    // Dynamic title
    $container.on('keyup', 'input[name*="[sibl_title]"]',
		function update_title() {
			var $this = $(this);
			var $title = $this.closest('.bloc').prev('h2').find('a');
			if (!$title.data('original')) {
				$title.data('original', $title.text());
			}
			$title.html($this.val().length > 0 ? $this.val() : $title.data('original'));
		}
    ).find('.blocs input[name*="[sibl_title]"]').trigger('keyup');

	function init_blocs(options) {
		var settings = $.extend(true, {
			accordion	: {
                header: "h2",
                expandDirection: "bottom",
                beforeSelectedIndexChanged : function (e, data) {
                    $container.find('.mceExternalToolbar').hide();
                    $container.find('.blocs').addClass('sliding');
                },
                selectedIndexChanged : function (e, data) {
                    $container.find('.blocs').removeClass('sliding');
                }
            },
			sortable	: {
                connectWith	: ".sidebar-sortable",
                stop		: function(e, ui) {
					// Reload wysiwyg editors
                    $container.find('.blocs .wys textarea').each(function() {
                        reload_wys_editor($(this).attr('id'));
					});
                    // Reorder input keys
                    var n = 0;
                    $container.find('.blocs .bloc').each(function() {
                        $(this).find('[name^="bloc["]').each(function() {
                            $(this).attr('name', $(this).attr('name').replace(/\[[0-9]+\]/g, "["+n+"]"));
                        });
                        n++;
                    });
                    init_blocs( );
                }
            }
		}, options);

		// Init accordion
        $container.find('.blocs').each(function() {
            var $active = $(this).find('.ui-state-active');
			settings.accordion.selectedIndex = $active.length ? $active.first().closest('li').index() : 0;
            $active.slice(1).removeClass('ui-state-active').addClass('ui-state-default').next('.wijmo-wijaccordion-content-active').removeClass('wijmo-wijaccordion-content-active');
			$(this).wijaccordion(settings.accordion);
            if (!$active.length) {
            	$(this).wijaccordion("activate", settings.accordion.selectedIndex);
			}
        });
        $container.find('.blocs .bloc').removeClass('ui-state-default');

		// Init sortable
        $container.find('.blocs ul').sortable(settings.sortable);

		// Add bloc's action buttons
        $container.find('.blocs h2').each(function() {
			var $this = $(this);

			// Buttons already set ?
			if ($this.find('.actions').length) {
				return true;
			}

            var $actions = $('<div class="actions"></div>');

            // Delete block
			var $delete = $('<button type="button" data-icon="trash" data-id="delete" class="ui-state-error action"><?= __('Delete') ?></button>').bind('click', function(e) {
				e.stopPropagation();
				e.stopImmediatePropagation();
				e.preventDefault();
				if (confirm('<?= _('Do you really wanna delete this block ?') ?>')) {
					var $this = $(this);
					var id = $(this).closest('li').find('[name*="[sibl_id]"]').val();

                    function delete_block() {
                        $this.closest('li').remove();
                    }

					if (id) {
						// Listen delete event
						$container.nosListenEvent({
							name	: 'Shao\\Sidebar',
							action	: ['delete'],
							id		: id
						}, delete_block);

						// Ajax query
						$container.nosAjax({
							url: 'admin/shao_sidebar/sidebar/crud/delete_bloc/'+id
						});
					} else {
                        delete_block();
					}
				}
			}).appendTo($actions);

			$this.append($actions);
		});

        $container.nosFormUI();
    }

	function reload_wys_editor(editor_id) {
        var tinymce_settings = $.extend(true, {}, tinyMCE.activeEditor.settings, {
            id			: editor_id,
            element_id	: editor_id
        });
        tinyMCE.execCommand('mceRemoveControl', false, editor_id);
        tinyMCE.init(tinymce_settings);
        tinymce.execCommand("mceAddControl", false, editor_id);
	}
});
</script>
<?php
$blocs = $item->blocs;
if (empty($blocs)) {
	$blocs = array(\Shao\Sidebar\Model_Sidebarbloc::forge());
}
?>
<div class="sidebar-blocs" id="sidebar-blocs-<?= $container_id ?>">
	<div class="sidebar-published">
		<div class="actions">
			<button type="button" data-icon="plusthick" class="add-new-bloc"><?= _('Add bloc') ?></button>
		</div>
		<h1 class="title"><?= _('Sidebar blocs') ?></h1>
		<div class="blocs">
			<ul class="sidebar-sortable">
				<?php $n = 0; foreach ($blocs as $bloc) { ?>
				<li>
					<input type="hidden" name="bloc[<?= $n ?>][sibl_id]" value="<?= $bloc->sibl_id ?>" />
					<h2><em><?= _('Bloc') ?></em></h2>
					<div class="bloc ui-state-default">
						<div class="bloc_title">
							<label>
								<input placeholder="<?= _('Title') ?>" type="text" name="bloc[<?= $n ?>][sibl_title]" value="<?= $bloc->sibl_title ?>" />
							</label>
						</div>
						<div class="wys">
							<?php
							echo \Nos\Renderer_Wysiwyg::renderer(array(
								'style' => 'width: 100%; height: 220px;',
								'name'	=> 'bloc['.$n.'][wysiwyg]',
								'value'	=> $bloc->wysiwygs->content,
								'renderer_options' => array(
									'mode' => 'exact',
								),
							));
							?>
						</div>
						<div class="options">
							<div class="bloc_class">
								<label>
									<?= _('CSS class property') ?> <input type="text" name="bloc[<?= $n ?>][sibl_class]" value="<?= $bloc->sibl_class ?>" />
								</label>
							</div>
						</div>
					</div>
				</li>
				<? $n++; } ?>
			</ul>
		</div>
	</div>

	<div class="sidebar-unpublished" id="sidebar-unpublished-<?= $container_id ?>">
		<h1 class="title"><?= _('Sidebar unpublished') ?></h1>
		<div class="blocs">
			<ul class="sidebar-sortable">

			</ul>
		</div>
	</div>
</div>
