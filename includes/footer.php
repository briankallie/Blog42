<footer class="row clearfix">
	<p class="column full center"> — 
		<a href="/">© <?php echo date("Y") ?> Brian Kallie</a> — 
		<a href="/admin/">Admin</a> —
	</p>
</footer><!--  /row -->

</div><!-- /container -->
<script src="//code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="/js/app-min.js"></script>
<?php
	if(isset($js)) echo $js;
?>
</body>
</html>