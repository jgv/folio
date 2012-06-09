var Folio = {
  Models: {},
  Collections: {},
  Routers: {},
  Views: {},
  init: function(data){
    this.projects = new Folio.Collections.Projects(data.projects);
    new Folio.Routers.Projects({ collection: this.projects });
    if (!Backbone.history.started) {
      Backbone.history.start();
      Backbone.history.started = true;
    }
  }
};


Folio.Routers.Projects = Support.SwappingRouter.extend({
  initialize: function(options){
    this.el = $("#projects");
    this.collection = options.collection;
  },
  
  routes: {
    '': 'index',
    '/projects/:id': 'show'
  },
  
  index: function(){
    var view = new Folio.Views.Index({ collection: this.collection });
    this.swap(view);
  },
  
  show: function(projectId){
    var project = this.collection.get(projectId);
    var view = new Folio.Views.Show({ model: project });
    this.swap(view);
  }  
});

Folio.Models.Project = Backbone.Model.extend({
  urlRoot: 'project',
  
  defaults: {
    'title': null,
    'description': null,
    'id': null
  }  
});

Folio.Collections.Projects = Backbone.Collection.extend({
  model: Folio.Models.Project,
  url: '/projects'
});


Folio.Views.Index = Support.CompositeView.extend({
  initialize: function(){
    _.bindAll(this, 'render');
    this.collection.bind('add', this.render);
  },
  
  className: 'project',
  
  render: function(){
    var self = this;
    this.collection.each(function(project){
      var data = {
        title: project.get('title'),
        image: project.get('images')[0].src,
        id: project.get('id')
      };
      proj = ich.index(data);
      $("#projects").append(proj);
    });

    return this;
  },

  // not using this atm
  // implement this for pretty urls that arent defined by ids
  cleanTitle: function(title) {
    var whitespace = $.trim(title);

    return whitespace.replace(' ', '-').toLowerCase();

  }

});

Folio.Views.Show = Support.CompositeView.extend({
  initialize: function(){
    _.bindAll(this, 'render');

  },
  
  render: function(){
    var self = this;
    var desc = unescape(self.model.get('description'));
    var data = {
      title: self.model.get('title'),
      images: self.model.get('images'),
      id: self.model.get('id'),
      description: desc
    };
    proj = ich.show(data);
    $("#projects").append(proj);
    
    $('#gallery').cycle({
      timeout: 0,
      next: "#gallery, #gallery img"
    });
    
    return this;
  
  }

});
