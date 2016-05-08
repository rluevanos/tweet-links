<div class="wrap tweet-links">
	<h2><?php echo _e( 'Tweet Links' ) ?></h2>

	<p>
		Use Tweet Links to generate shortcodes for tweet, retweet, and reply. These can be used anywhere within a post or page. You can also enable a custom tweet links message beneath each post or page. 
	</p>

	<form class="form-table" name="tweet_links_form" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<p>
			<div class="postbox">
				<div class="inside">
					<h3>Settings</h3> 
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
									<label for="tweet_links_handle">Twitter Handle</label>
								</th>
								<td>
									@<input name="tweet_links_handle" type="text" id="tweet_links_handle" value="<?php echo ( ! empty( $data['handle'] ) ? esc_html( $data['handle'] ) : '' ) ?>" class="regular-text" />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="tweet_links_shortener_on">Shorten URL</label>
								</th>
								<td>
									<label for="tweet_links_shortener_on">
									<input name="tweet_links_shortener_on" type="checkbox" id="tweet_links_shortener_on" value="1" <?php echo ( ! empty( $data['shortener_on'] ) ? 'checked' : '' ) ?>/>
										<i>( http://example.com/?p=1234 )</i>
									</label>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="tweet_links_blank_on">Open In Seperate Tab</label>
								</th>
								<td>
									<label for="tweet_links_blank_on">
									<input name="tweet_links_blank_on" type="checkbox" id="tweet_links_blank_on" value="1" <?php echo ( ! empty( $data['blank_on'] ) ? 'checked' : '' ) ?>/>
										Links will open in new tab
									</label>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="tweet_links_tags_on">Include Tags In Tweet</label>
								</th>
								<td>
									<label for="tweet_links_tags_on">
									<input name="tweet_links_tags_on" type="checkbox" id="tweet_links_tags_on" value="1" <?php echo ( ! empty( $data['tags_on'] ) ? 'checked' : '' ) ?>/>
										#example_tag
									</label>
								</td>
							</tr>
						</tbody>
					</table>
					<br />
					<p>
						<h4>Use the following shortcodes anywhere in a post or page:</h4>
						<ul>
							<li>[tweetlinks type="tweet" text="Tweet this post"]</li>
							<li>[tweetlinks type="retweet" text="Retweet this post"]</li>
							<li>[tweetlinks type="reply" text="Reply to this post on Twitter"]</li>
						</ul>
					</p>
				</div>
			</div>
		</p>
		<p>
			<div class="postbox">
				<div class="inside">
					<h3>Post/Page Options</h3> 
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
									<label for="tweet_links_message_on">Show Tweet Message</label>
								</th>
								<td>
									<label for="tweet_links_message_on">
									<input name="tweet_links_message_on" type="checkbox" id="tweet_links_message_on" value="1" <?php echo ( ! empty( $data['message_on'] ) ? 'checked' : '' ) ?>/>
										Show custom message
									</label>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="tweet_links_message_post_on">Show For Posts</label>
								</th>
								<td>
									<label for="tweet_links_message_post_on">
									<input name="tweet_links_message_post_on" type="checkbox" id="tweet_links_message_post_on" value="1" <?php echo ( ! empty( $data['message_post_on'] ) ? 'checked' : '' ) ?>/>
									Recommended
									</label>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="tweet_links_message_page_on">Show For Pages</label>
								</th>
								<td>
									<label for="tweet_links_message_page_on">
									<input name="tweet_links_message_page_on" type="checkbox" id="tweet_links_message_page_on" value="1" <?php echo ( ! empty( $data['message_page_on'] ) ? 'checked' : '' ) ?>/>
									</label>
								</td>
							</tr>
							<tr valign="top">
								<td colspan="2">
									<label for="tweet_links_custom_message">
										Example: <i>Share this on Twitter: {TWEET}, {RETWEET}, or {REPLY} with a comment.</i>
									</label>
									<textarea name="tweet_links_custom_message" id="tweet_links_custom_message" class="large-text" rows="3"><?php echo ( ! empty( $data['custom_message'] ) ? esc_html( $data['custom_message'] ) : '' ) ?></textarea>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</p>

		<p class="disclaimer">
			<strong>NOTE:</strong> You will need to add a tweet id to your post/page for retweet or reply to work correctly. You can grab a tweet id from the twitter url: https://twitter.com/username/status/<span class="tweet-id">0123456789012345678</span>
		</p>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" />
		</p>
	</form>
</div>
