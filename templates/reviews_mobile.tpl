{reviews placeId=$smarty.request.placeId var="reviews"}
{getPlaceInfo var="placeInfo" placeId=$smarty.request.placeId categoryId=$smarty.request.categoryId locationId=$smarty.request.locationId}
<div id="reviews">
	<h3>Reviews for {$placeInfo.placeName}</h3>
	
	{if $reviews}
		<p>Want to give us a <a href="submitReview.html?placeId={$smarty.request.placeId}&type=place&id={$smarty.request.placeId}&categoryId={$smarty.request.categoryId}&locationId={$smarty.request.locationId}">review</a> for ?</p>
		<p>Number of reviews: {$reviews.reviewInfo.count}<br />
		Average Score: {$reviews.reviewInfo.average}/10
		</p>
		<ul>
			{foreach from=$reviews.reviews key="re" item="view"}
				<ul class="reviewContainer" id="reCon{$view.reviewId}">
					<li class="reviewInfo">{$view.addedBy} - {$view.dateAdded|date_format:"%a, %d %B %Y"}</li>
					<ul class="reviewData">
						<li><span>Score:</span> {$view.rating}/10</li>
						<li><span>Comment:</span> {$view.comment}</li>
					</ul>
					<li class="repReview"><a href="report.html?reviewId={$view.reviewId}" id="reportReview{$view.reviewId}" class="reportReview" title="Report this review">Report this review</a></li>
				</ul>
			{/foreach}
		</ul>
	{else}
		<span>Sorry, we haven't got any reviews, want to give us <a href="submitReview.html??placeId={$smarty.request.placeId}&type=place&id={$smarty.request.placeId}&categoryId={$smarty.request.categoryId}&locationId={$smarty.request.locationId}">one</a>?</span>
	{/if}
</div>