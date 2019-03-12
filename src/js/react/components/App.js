import React from 'react';
import { connect } from 'react-redux';
import ListView from './ListView';
import PostDetail from './PostDetail';

class App extends React.Component {

  componentDidMount () {
    this.props.dispatch ({
      type: "LOADING_SUCCESS",
      postType: 'aromatherapie',
      value: postsData
    })
  }

  render () {
    //console.log("App Render", this.props)
    return (
      <div className="row">
        <div className="col-sm-4">
          {
            this.props.posts.length > 0 ?
            <ListView data={this.props.posts} />
            : null
          }
        </div>
        <div className="col-sm-8" style={{height: "100em"}}>
          {
            this.props.activePost ? <PostDetail /> : null
          }
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
