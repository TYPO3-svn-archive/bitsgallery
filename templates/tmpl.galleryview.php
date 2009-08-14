	

<?php $entryList = $this->get('entryList'); ?>
<ul id="gallery">
<?php for($entryList->rewind(); $entryList->valid(); $entryList->next()): $entry = $entryList->current();	?>
		<li>
				<?php //<span class="panel-overlay">Lorem Ipsum!!!</span> ?>
				<?php echo $entry->printAsImage( $this->get('imgDirectory') . $entry->get('image') ); ?>
				
				
			</li>
	<?php endfor; ?>
</ul>


<?php 

	


?>
