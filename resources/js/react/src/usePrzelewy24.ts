import { usePage } from '@inertiajs/react';
import axios from 'axios';
import { useState } from 'react';

type TTransactionRegisterRequestData = Record<string, unknown>;

export const usePrzelewy24 = () => {
  const page = usePage<{
    przelewy24: {
      transaction: {
        registerRoute: string;
      };
    };
  }>();

  const [isTransactionRegisterLoading, setIsTransactionRegisterLoading] = useState<boolean>(false);

  return {
    transaction: {
      isRegisterLoading: isTransactionRegisterLoading,
      registerRequest: ({
        data,
        onError,
        onSuccess = ({ redirectUrl }) => {
          window.location.href = redirectUrl;
        },
      }: {
        data: TTransactionRegisterRequestData;
        onSuccess?: ({ redirectUrl }: { redirectUrl: string }) => void;
        onError?: ({ errors }: { errors: string[] }) => void;
      }) => {
        setIsTransactionRegisterLoading(true);
        axios
          .post(page.props.przelewy24.transaction.registerRoute, data)
          .then((response) => {
            setIsTransactionRegisterLoading(false);
            onSuccess?.({ redirectUrl: response.data.redirectUrl });
          })
          .catch((error) => {
            setIsTransactionRegisterLoading(false);
            onError?.({ errors: [error.message] });
          });
      },
    },
  };
};
