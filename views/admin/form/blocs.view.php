<?php
$container_id = uniqid();
$index = 0;
?>
<div class="sidebar-blocs" id="sidebar-blocs-<?= $container_id ?>">

    <div class="sidebar-blocs-column sidebar-unpublished">
        <div class="actions">
            <button type="button" data-icon="plusthick" class="add-new-bloc"><?= _('Add bloc') ?></button>
        </div>
        <h1 class="title">
			<?= _('Unpublished blocks') ?>
		</h1>
		<p class="legend">
			<?= _('To <strong>publish</strong> a block drag it to the right column.') ?>
		</p>
        <div class="blocs ui-widget-header">
            <ul class="sidebar-sortable">
				<?php
				foreach ($item->blocs as $bloc) {
					if (!$bloc->sibl_published) {
						?>
						<li>
							<?= \View::forge('shao_sidebar::admin/form/bloc', array(
								'bloc_index'	=> $index++,
								'bloc'		=> $bloc,
							), false); ?>
						</li>
						<?php
					}
				}
				?>
            </ul>
        </div>
    </div>

	<div class="sidebar-blocs-column sidebar-published">
		<h1 class="title">
			<?= _('Published blocks') ?>
		</h1>
        <p class="legend">
			<?= _('To <strong>unpublish</strong> a block drag it to the left column.') ?>
        </p>
		<div class="blocs ui-widget-header">
			<ul class="sidebar-sortable">
				<?php
				foreach ($item->blocs as $bloc) {
					if ($bloc->sibl_published) {
						?>
						<li>
							<?= \View::forge('shao_sidebar::admin/form/bloc', array(
								'bloc_index'	=> $index++,
								'bloc'		=> $bloc,
							), false); ?>
                        </li>
						<?php
					}
				}
				?>
			</ul>
		</div>
	</div>
</div>

<style type="text/css">
    .sidebar-blocs .legend {
		color: #888;
		line-height: 1.25em;
		margin: .5em 0 0 0;
    }
    .sidebar-blocs .blocs {
        margin: 1em 0;
        border: 1px solid #ccc;
        border-radius: 6px;
        background-color: #cccccc;
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
    .sidebar-blocs-column {
        float: left !important;
        width: 35em;
	}
    .sidebar-blocs-column .blocs {
        min-height: 450px;
    }
    .sidebar-published {
        margin-left: 3em;
    }
    .sidebar-unpublished .blocs {
        position: relative;
        border: 1px dashed #cccccc;
		background: #fafafa;
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

        // Create skeleton
        var settings = <?= json_encode(array('skeleton' => \View::forge('shao_sidebar::admin/form/bloc', array(
			'bloc_index'	=> 0,
			'bloc'			=> \Shao\Sidebar\Model_Sidebarbloc::forge(),
		), false)->render())); ?>;

        var $container = $('#sidebar-blocs-<?= $container_id ?>');

        // Add new bloc
        $('.add-new-bloc').on('click', function add_bloc(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            // Create new bloc
            var $new = $(settings.skeleton);

            // Reset inputs, increment keys and ids
            var n = $container.find('.bloc').length;
            $new.find('[name^="bloc["]').each(function() {
                $(this).attr('name', $(this).attr('name').replace(/bloc\[[0-9]*\]/g, "bloc["+n+"]")).val('');
            });
            var editor_id = $new.find('.wys textarea').attr('id')+n;
            $new.find('.mceEditor').remove();
            $new.find('.wys textarea').attr('id', editor_id).val('').show();

            // Append to DOM
            $(this).closest('.sidebar-blocs-column').find('ul').append($('<li></li>').append($new));

            // Reload wysiwyg editor
            reload_wys_editor(editor_id);

            // Reload UI
            $container.find('.blocs').wijaccordion('destroy');
            $container.find('.blocs ul').sortable('destroy');
            init_blocs();
//        var default_accordion = $container.find('.sidebar-published .blocs .wijmo-wijaccordion-content-active').first().closest('li').index();
//        $container.find('.sidebar-published .blocs').wijaccordion("activate", default_accordion);
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
								console.log(this.nodeName);
                            });
                            n++;
                        });
                        // Published/unpublished
                        $container.find('.blocs input[name*="[sibl_published]"]').each(function() {
                            $(this).val($(this).closest('.sidebar-published').length ? '1' : '0');
                        });
                        // Reinit blocs UI
                        init_blocs();

                    }
                }
            }, options);

            // Init accordion
            $container.find('.blocs').each(function() {
                var $active = $(this).find('.ui-state-active');

                var accordion_settings = $.extend({}, settings.accordion);

//			if ($(this).closest('.sidebar-published').length) {
                accordion_settings.selectedIndex = $active.length ? $active.first().closest('li').index() : 0;
                $active = $active.slice(1);
//			} else {
//                accordion_settings.disabled = true;
//			}

                if ($active.length) {
                    $active.removeClass('ui-state-active').addClass('ui-state-default')
                            .next('.wijmo-wijaccordion-content-active').removeClass('wijmo-wijaccordion-content-active');
                }
                $(this).wijaccordion(accordion_settings);
                if ($active) {
                    $(this).wijaccordion("activate", accordion_settings.selectedIndex);
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
