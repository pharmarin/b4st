import React from 'react';
import { RenderHTML } from './RenderHTML';

export default class ListView extends React.Component {
  constructor() {
    super()
    this.state = {
      active: null
    }
  }

  itemSelected (id) {
    this.setState({ active: id })
  }

  render () {
    return (
      <ul className="list-group">
        {
          this.props.data ? this.props.data.map(
            (item, index) =>
            <li
              className={
                item.id === this.state.active ?
                 "list-group-item list-group-item-action active"
                 : "list-group-item list-group-item-action"
              }
              key={ index }
              onClick={ this.itemSelected.bind(this, item.id) }
              >
              {
                RenderHTML(item.title.rendered)
              }
            </li>
          ) : null
        }
      </ul>
    )
  }
}
