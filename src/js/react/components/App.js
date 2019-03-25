import React from 'react';
import { connect } from 'react-redux';
import ListView from './ListView';
import PostDetail from './PostDetail';

var WPAPI = require( 'wpapi' );
var _ = require( 'lodash' );

function getAll( request ) {
  return request.then(function( response ) {
    if ( ! response._paging || ! response._paging.next ) {
      return response;
    }
    // Request the next page and return both responses as one collection
    return Promise.all([
      response,
      getAll( response._paging.next )
    ]).then(function( responses ) {
      return _.flatten( responses );
    });
  });
}

const namespace = 'wp/v2';
var wp = new WPAPI({ endpoint: 'https://pharmacie.marionetmarin.fr/wp-json/' });
wp.aromatherapie = wp.registerRoute(namespace, '/aromatherapie/(?P<id>)');

class App extends React.Component {

  componentDidMount () {
    wp.aromatherapie().then(function( data ) {
      console.log("Data", data);
    }).catch(function( err ) {
      console.log("Error", err);
    });
    getAll( wp.aromatherapie() ).then(function( allPosts ) {
      console.log("allPosts", allPosts);
    });
    this.props.dispatch ({
      type: "LOADING_SUCCESS",
      postType: 'aromatherapie',
      value: postsData
    })
  }

  render () {
    //console.log("App Render", this.props)
    return (
      <div className="row app">
        <div className="col-sm-4 card listview">
          {
            this.props.posts.length > 0 ?
            <ListView data={this.props.posts} />
            : null
          }
        </div>
        <div className="col-sm-8 postdetail">
          <PostDetail post={this.props.posts.find((item) => item.id === this.props.activePost)} />
        </div>
      </div>
    )
  }
}

const mapStateToProps = (state) => {
  return {
    posts: state.apiReducer.aromaPosts,
    activePost: state.appReducer.activePost
  }
}

export default connect(mapStateToProps)(App)
