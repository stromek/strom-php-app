import React from 'react'
import {BrowserRouter, Routes, Route, Link, Outlet, useParams, useNavigate} from "react-router";

// Stranky
import PageThread from './../thread/thread.container.js'
import AppLink from "../../components/AppLink.jsx";


const Home = (props) => {
  return <div>Home</div>
}

const Thread = (props) => {
  return <div>Thread</div>
}

const About = (a, b, c) => {
  return (
    <div>
      About
    </div>
  )
}


const App = (props) => {
  return (
    <React.StrictMode>
      <nav>
        <AppLink to="/">Home</AppLink>
        <AppLink to="/about/">About</AppLink>
        <AppLink to="/thread/26cA9OuRGD1ZLER8WaRDpZxz3sV5w94Loyqj7vYf8KbvQvQx2mzCbVVHzzY0XD6sQFE7sNsJ7gO5RSDnRZbMdA9Nmid3sXe7KcEs/">Thread</AppLink>
      </nav>

      <Outlet />
    </React.StrictMode>
  )
}

const App404 = (props) => {
  return <div>Error 404</div>
}


export default (props) => {
  return (
    <BrowserRouter basename="/app/">
      <Routes>
        <Route path="/:clientKey" element={<App />}>
          <Route index element={<Home />} />

          <Route path="thread" element={<PageThread />}>
            <Route path="concept" element={<About />} />
            <Route path=":threadHash" element={<Thread />} />
          </Route>
          <Route path="about/" element={<About />} />
        </Route>

        <Route path="*" element={<App404 />} />
      </Routes>
    </BrowserRouter>
  )
}
