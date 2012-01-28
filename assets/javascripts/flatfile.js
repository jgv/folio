var Flatfile = {
  Models: {},
  Collections: {},
  Routers: {},
  Views: {},
  init: function(data){
    this.projects = new Flatfile.Collections.Projects(data.projects);
    new Flatfile.Routers.Projects({ collection: this.projects });
    if (!Backbone.history.started) {
      Backbone.history.start();
      Backbone.history.started = true;
    }
  }
};


Flatfile.Routers.Projects = Support.SwappingRouter.extend({
  initialize: function(options){
    this.el = $("#projects");
    this.collection = options.collection;
  },
  
  routes: {
    '': 'index',
    '/projects/:id': 'show'
  },
  
  index: function(){
    var view = new Flatfile.Views.Index({ collection: this.collection });
    this.swap(view);
  },
  
  show: function(projects){
    var projet = this.collection.get(projectId);
    var self = this;
    project.fetch({
      success: function(){
        var view = new Flatfile.Views.Show({ model: project });
        self.swap(view);
      }
    });
  }  
});

Flatfile.Models.Project = Backbone.Model.extend({
  urlRoot: 'project',
  
  defaults: {
    'title': null,
    'description': null,
    'id': null
  }  
});

Flatfile.Collections.Projects = Backbone.Collection.extend({
  model: Flatfile.Models.Project,
  url: '/projects'
});


Flatfile.Views.Index = Support.CompositeView.extend({
  initialize: function(){
    _.bindAll(this, 'render');
    this.collection.bind('add', this.render);
  },
  
  className: 'project',
  
  render: function(){
    var self = this;
    this.collection.each(function(project){
      console.log(project);
      var data = {
        title: project.get('title'),
        images: project.get('images'),
        id: project.cid
      };
      proj = ich.project(data);
      $("#projects").append(proj)
    });

    return this;
  }
});