	

<?php $entryList = $this->get('entryList'); ?>
<div class="demo">
<div id="main_image"></div>
<ul class="gallery_demo_unstyled">
<?php for($entryList->rewind(); $entryList->valid(); $entryList->next()): $entry = $entryList->current();	?>
		<li>

				<?php echo $entry->printAsImage( $this->get('imgDirectory') . $entry->get('image') ); ?>
				
				
			</li>
	<?php endfor; ?>
</ul>
<p class="nav"><a href="#" onclick="$.galleria.prev(); return false;">&laquo; previous</a> | <a href="#" onclick="$.galleria.next(); return false;">next &raquo;</a></p>
</div>

<?php 

	


?>
