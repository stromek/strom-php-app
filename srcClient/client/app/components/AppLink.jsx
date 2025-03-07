import {useNavigate, useParams} from "react-router";
import React from "react";

export default function AppLink({ to, children, ...props }) {
  const navigate = useNavigate()
  const { clientKey } = useParams();

  const path = `/${clientKey}${to}`;
  const handleNavigate = (e) => {
    navigate(path, {replace: true});
    e.preventDefault();
  }

  return (<a href={'/app'+path} onClick={handleNavigate}>{children}</a>);
}