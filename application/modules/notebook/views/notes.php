<div class="chat-main-row">
<div class="chat-main-wrapper">
              <!-- .aside -->
              <aside class="aside-xl notes-left" id="note-list">
                <section class="vbox flex">
                  <header class="notes-header clearfix">
                    <span class="pull-right m-t"><button class="btn btn-custom btn-sm btn-icon" id="new-note" data-toggle="tooltip" data-placement="right" title="New"><i class="fa fa-plus"></i></button></span>
                    <p class="h3"><?=lang('notes')?></p>
                    <div class="input-group m-t-sm m-b-sm">
                      <span class="input-group-addon input-sm btn-success"><i class="fa fa-search"></i></span>
                      <input type="text" class="form-control input-sm" id="search-note" placeholder="search">
                    </div>
                  </header>
                  <section>
                    <section>
                      <section>
                        <div class="padder">
                          <!-- note list -->
                          <ul id="note-items" class="list-group list-group-sp"></ul>
                            <!-- templates -->
                            <script type="text/template" id="item-template">
                              <div class="view" id="note-<%- id %>">
                                <button class="destroy close hover-action">&times;</button>
                                <div class="note-name">
                                  <strong>
                                  <%- (name && name.length) ? name : 'New note' %>
                                  </strong>
                                </div>
                                <div class="note-desc">
                                  <%- description.replace(name,'').length ? description.replace(name,'') : 'empty note' %>
                                </div>
                                <span class="text-xs text-muted"><%- moment(parseInt(date)).format('MMM Do, h:mm a') %></span>
                              </div>
                            </script>
                            <!-- / template  -->
                          <!-- note list -->
                          <p class="text-center">&nbsp;</p>
                        </div>
                      </section>
                    </section>
                  </section>
                </section>
              </aside>
              <!-- /.aside -->
              <aside id="note-detail" class="notes-right">
                <script type="text/template" id="note-template">
                  <section class="vbox">
                    <header class="header bg-white lter box-shadow">
                      <p id="note-date"><?=lang('created')?> <%- moment(parseInt(date)).format('MMM Do, h:mm a') %></p>
                    </header>
                    <section class="bg-light lter">
                      <section class="hbox stretch">
                        <aside>
                          <section class="vbox note-wrapper">
                            <section class="paper">
<textarea class="form-control notes" placeholder="Type your note here"><%- description %></textarea>
                            </section>
                          </section>
                        </aside>
                      </section>
                    </section>
                  </section>
                </script>
              </aside>
          </div>
          </div>
