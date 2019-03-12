import React from 'react';
import { connect } from 'react-redux';
import ListView from './ListView';

import Store from '../store/configureStore';

class App extends React.Component {
  constructor () {
    super()
  }

  componentDidMount () {
    this.props.dispatch ({
      type: "LOADING_SUCCESS",
      postType: 'aromatherapie',
      value: postsData
    })
  }

  render () {
    console.log("App Render", this.props)
    return (
      <div className="row">
        <div className="col-sm-4">
          {
            this.props.posts.length > 0 ?
            <ListView data={this.props.posts} />
            : null
          }
        </div>
        <div className="col-sm-8" style={{height: "100em"}}></div>
      </div>
    )
  }
}

const mapStateToProps = (state) => {
  console.log("MapState", state)
  return {
    posts: state.aromaPosts,
    isLoading: {
      pharmacie: state.pharmaIsLoading,
      aromatherapie: state.aromaIsLoading,
      phytotherapie: state.phytoIsLoading
    }
  }
}

export default connect(mapStateToProps)(App)
